import cv2
import face_recognition
import numpy as np
import json
import requests
from flask import Flask, Response, jsonify, request
import base64
import threading
import time
from flask_cors import CORS

app = Flask(__name__)
CORS(app)


# URL API untuk mendapatkan wajah yang terdaftar
API_URL = "http://127.0.0.1/absensi/absen/get_registered_faces"
id_user_video = None
# Fungsi untuk mendapatkan wajah terdaftar
def get_registered_faces():
    known_face_encodings = []
    known_face_names = []
    known_face_ids = []
    try:
        # Kirim permintaan GET ke API
        response = requests.get(API_URL)
        print("Status Code:", response.status_code)
        print("Response Headers:", response.headers)
        print("Response Text (raw):", response.text)

        # Periksa status kode
        if response.status_code == 200:
            # Coba parsing JSON
            try:
                registered_faces = response.json()

                # Proses setiap wajah yang terdaftar
                for face in registered_faces:
                    try:
                        # Decode base64 face encoding jika perlu
                        if 'face_encoding' in face:
                            # Jika encoding dalam bentuk base64, decode terlebih dahulu
                            if isinstance(face['face_encoding'], str):
                                encoding = json.loads(base64.b64decode(face['face_encoding']).decode('utf-8'))
                            else:
                                encoding = face['face_encoding']

                            # Konversi ke numpy array
                            encoding_array = np.array(encoding)

                            # Ensure encoding is 128-dimensional
                            if encoding_array.shape == (128,):
                                known_face_encodings.append(encoding_array)
                                known_face_names.append(face.get('name', 'Unknown'))
                                known_face_ids.append(face.get('id_user', 'Unknown'))
                            else:
                                print(f"Skipping invalid encoding shape: {encoding_array.shape}")
                                continue

                    except (json.JSONDecodeError, TypeError, ValueError) as e:
                        print(f"Error processing face encoding: {e}")
                        continue

                print(f"Berhasil memuat {len(known_face_encodings)} wajah terdaftar")
                return known_face_encodings, known_face_names, known_face_ids

            except json.JSONDecodeError as e:
                print(f"Error parsing JSON: {e}")
                print("Response text:", response.text)
        else:
            print(f"Gagal mengambil data. Kode status: {response.status_code}")
            print("Response text:", response.text)

    except requests.RequestException as e:
        print(f"Kesalahan jaringan: {e}")

    return [], [], []

# Dapatkan wajah terdaftar saat inisialisasi
known_face_encodings, known_face_names, known_face_ids = get_registered_faces()

# Inisialisasi kamera
video_capture = cv2.VideoCapture(0)
def update_registered_faces():
    global known_face_encodings, known_face_names, known_face_ids
    while True:
        # Dapatkan wajah yang terdaftar setiap 10 detik
        known_face_encodings, known_face_names, known_face_ids = get_registered_faces()
        time.sleep(10)

# Mulai thread untuk memperbarui wajah terdaftar secara periodik
update_thread = threading.Thread(target=update_registered_faces)
update_thread.daemon = True
update_thread.start()

def generate_frames():
    global id_user_video
    while True:
        # Ambil frame dari kamera
        ret, frame = video_capture.read()

        if not ret:
            break

        # Konversi frame ke RGB
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

        # Deteksi wajah
        face_locations = face_recognition.face_locations(rgb_frame)
        face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

        # Proses setiap wajah yang terdeteksi
        for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
            # Cocokkan wajah dengan wajah yang terdaftar
            name = "Unknown"
            id_user_video = None
            # Pastikan ada wajah yang terdaftar untuk dibandingkan
            if known_face_encodings:
                # Gunakan toleransi yang lebih longgar
                matches = face_recognition.compare_faces(known_face_encodings, face_encoding, tolerance=0.6)

                # Gunakan jarak wajah terkecil jika ada kecocokan
                face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
                best_match_index = np.argmin(face_distances)

                if matches[best_match_index]:
                    name = known_face_names[best_match_index]
                    id_user_video = known_face_ids[best_match_index]
            # Gambar kotak dan nama di sekitar wajah
            cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
            cv2.putText(frame, name, (left + 6, bottom - 6),
                        cv2.FONT_HERSHEY_DUPLEX, 0.5, (0, 255, 0), 1)

        # Encode frame sebagai JPEG
        _, buffer = cv2.imencode('.jpg', frame)
        frame = buffer.tobytes()

        # Kirim frame ke browser
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n')

video_capture = None

def start_video_capture():
    global video_capture
    if video_capture is None or not video_capture.isOpened():
        video_capture = cv2.VideoCapture(0)  # Mulai kamera
        if not video_capture.isOpened():
            print("Tidak dapat memulai capture video!")

@app.route('/video_feed')
def video_feed():
    start_video_capture()
    return Response(generate_frames(),
                    mimetype='multipart/x-mixed-replace; boundary=frame')

@app.route('/get_current_id', methods=['GET'])
def get_current_id():
    global id_user_video, known_face_names, known_face_encodings, known_face_ids
    if id_user_video is None:
        return jsonify({"error": "No user detected"}), 400  # Error jika tidak ada user
    
    try:
        index = known_face_ids.index(id_user_video)
        name = known_face_names[index]
        face_encoding = known_face_encodings[index].tolist()  # Konversi ke list
        return jsonify({
            "id_user": id_user_video,
            "name": name,
            "face_encoding": face_encoding
        })
    except ValueError:
        return jsonify({"error": "User not found"}), 404
	

@app.route('/control_video_feed', methods=['POST'])
def control_video_feed():
    global video_capture
    try:
        action = request.json.get("action")
        if not action:
            return jsonify({"error": "Missing action parameter"}), 400
        
        if action == "start":
            if video_capture is None or not video_capture.isOpened():
                video_capture = cv2.VideoCapture(0)  # Mulai kamera
                if not video_capture.isOpened():
                    return jsonify({"error": "Unable to start video capture"}), 500
            return jsonify({"status": "started"}), 200
        elif action == "stop":
            if video_capture and video_capture.isOpened():
                video_capture.release()  # Hentikan kamera
                video_capture = None
                return jsonify({"status": "stopped"}), 200
            else:
                return jsonify({"error": "Video capture is not started"}), 400
        else:
            return jsonify({"error": "Invalid action"}), 400
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5000, debug=True)
