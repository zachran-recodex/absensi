import face_recognition
import numpy as np
from flask import Flask, request, jsonify
import os
from werkzeug.utils import secure_filename

app = Flask(__name__)

UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['ALLOWED_EXTENSIONS'] = {'png', 'jpg', 'jpeg'}

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in app.config['ALLOWED_EXTENSIONS']

@app.route('/verify_face', methods=['POST'])
def verify_face():
    if 'file' not in request.files:
        return jsonify({"status": "failed", "message": "No file part"})
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({"status": "failed", "message": "No selected file"})
    
    if file and allowed_file(file.filename):
        filename = secure_filename(file.filename)
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        file.save(filepath)

        # Load the uploaded image and encode it
        uploaded_image = face_recognition.load_image_file(filepath)
        uploaded_face_encoding = face_recognition.face_encodings(uploaded_image)

        if len(uploaded_face_encoding) == 0:
            return jsonify({"status": "failed", "message": "No face found in the image"})

        # Compare the uploaded face with a known face (assuming you already have the known face encoding)
        known_face_encoding = get_known_face_encoding()  # Replace with your actual method to get the reference face encoding

        matches = face_recognition.compare_faces([known_face_encoding], uploaded_face_encoding[0])

        if True in matches:
            return jsonify({"status": "success", "message": "Face matched!"})
        else:
            return jsonify({"status": "failed", "message": "Wajah tidak cocok!"})

def get_known_face_encoding():
    # Load a reference image, for example 'reference_image.jpg'
    reference_image = face_recognition.load_image_file('reference_image.jpg')
    known_face_encoding = face_recognition.face_encodings(reference_image)[0]
    return known_face_encoding

if __name__ == '__main__':
    app.run(debug=True)
