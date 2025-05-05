// Firebase Config
const firebaseConfig = {
  apiKey: "AIzaSyAgJI8gWLkfP0BJXGodj3ovthOhgzAmCWo",
  authDomain: "etpanthiya-aa44b.firebaseapp.com",
  projectId: "etpanthiya-aa44b",
  storageBucket: "etpanthiya-aa44b.appspot.com",
  messagingSenderId: "6707718138",
  appId: "1:6707718138:web:c2b8fb53ad93d5ed762643"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();

async function uploadToImgur(file) {
  const formData = new FormData();
  formData.append("image", file);
  const res = await fetch("https://api.imgur.com/3/image", {
    method: "POST",
    headers: {
      Authorization: "Client-ID 8c21d7cbdbe85c7"
    },
    body: formData
  });
  const data = await res.json();
  return data.data.link;
}

async function uploadToUguu(file) {
  const formData = new FormData();
  formData.append("file", file);
  const res = await fetch("https://uguu.se/upload.php", {
    method: "POST",
    body: formData
  });
  const data = await res.json();
  return data.files[0].url;
}

async function submitForm() {
  const title = document.getElementById("title").value;
  const tab = document.getElementById("tab").value;
  const category = document.getElementById("category").value;
  const caption = document.getElementById("caption").value;
  const description = document.getElementById("description").value;
  const embed = document.getElementById("embed").value;
  const datetime = document.getElementById("datetime").value;
  const imageFile = document.getElementById("image").files[0];
  const docFile = document.getElementById("document").files[0];

  let imageUrl = "", docUrl = "";

  if (imageFile) imageUrl = await uploadToImgur(imageFile);
  if (docFile) docUrl = await uploadToUguu(docFile);

  await db.collection(tab).add({
    title,
    category,
    caption,
    description,
    embed,
    datetime,
    imageUrl,
    docUrl,
    createdAt: new Date().toISOString(),
  });

  alert("Uploaded successfully!");
}
