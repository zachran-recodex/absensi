import face_recognition
import sys
import json

# The entry point for this script
if __name__ == "__main__":
    image_path = sys.argv[1]  # Get image path from command-line argument

    # Load the image and get face encodings using face_recognition library
    image = face_recognition.load_image_file(image_path)

    # Get face encodings (this returns a list of encodings, but we assume only one face per image)
    face_encodings = face_recognition.face_encodings(image)

    if face_encodings:
        encoding = face_encodings[0]  # Get the encoding for the first face found
        encoding_128 = encoding[:128]  # Ensure it is 128-dimensional
        print(json.dumps(encoding_128.tolist()))  # Print the face encoding as JSON
    else:
        print("Face encoding extraction failed.")
