const toggleButton = document.getElementById("toggleButton");
const cardChat = document.getElementById("cardChat");

toggleButton.addEventListener("click", function () {
  if (cardChat.style.display === "none") {
    cardChat.style.display = "flex";
  } else {
    cardChat.style.display = "none";
  }

  var haha = cardChat.style.display;

  console.log(haha);
});

document.addEventListener("DOMContentLoaded", function () {
  const chatForm = document.getElementById("chatForm");
  const chatMessages = document.getElementById("chatMessages");
  const currentUsernameInput = document.getElementById("currentUsername");
  const currentUsername = currentUsernameInput.value;

  const currentProfilePicInput = document.getElementById("currentProfilePic");
  const currentProfilePic = currentProfilePicInput.value;

  chatForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const pesanInput = document.getElementById("messageInput");
    const pesan = pesanInput.value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "chat.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log(xhr.responseText);
          loadChat();
        } else {
          console.error("Terjadi kesalahan:", xhr.statusText);
        }
      }
    };
    xhr.send("pesan=" + encodeURIComponent(pesan));

    pesanInput.value = "";
  });

  function loadChat() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "chat.txt", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          const pesanArray = xhr.responseText.split("\n");

          chatMessages.innerHTML = "";

          pesanArray.forEach(function (pesan) {
            if (pesan.trim() !== "") {
              const pesanParts = pesan.split(": ");
              const pengirim = pesanParts[0].trim();
              const isiPesan = pesanParts[1].trim();
              const dato = pesanParts[2].trim();

              const containerPesan = document.createElement("div");

              if (pengirim === currentUsername) {
                containerPesan.classList.add(
                  "flex",
                  "flex-row-reverse",
                  "justify-end",
                  "items-center",
                  "flex-end",
                  "gap-3",
                  "mb-2"
                );
              } else {
                containerPesan.classList.add(
                  "flex",
                  "flex-row",
                  "justify-start",
                  "items-center",
                  "flex-start",
                  "gap-3",
                  "mb-2"
                );
              }

              // Buat elemen untuk menampilkan foto profil
              const fotoProfil = document.createElement("div");
              fotoProfil.classList.add(
                "h-5",
                "w-5",
                "rounded-full",
                "bg-transparent"
              );
              const fotoProfilImg = document.createElement("img");
              fotoProfilImg.classList.add(
                "object-cover",
                "min-h-5",
                "min-w-5",
                "rounded-full"
              );

              fotoProfilImg.src = currentProfilePic;
              fotoProfilImg.alt = "";

              const pengirimElement = document.createElement("p");
              pengirimElement.textContent = pengirim;
              pengirimElement.classList.add("text-xs", "text-gray-600");

              const pesanElement = document.createElement("div");
              pesanElement.textContent = isiPesan;

              const datoInMilliseconds = dato * 1000;
              const jsDate = new Date(datoInMilliseconds);

              const hour = jsDate.getHours();
              const minute = jsDate.getMinutes();

              const date = jsDate.getDate();
              const month = jsDate.getMonth() + 1;
              const year = jsDate.getFullYear();

              const formattedDate = `${hour}:${minute < 10 ? '0' + minute : minute} - ${date}/${month}/${year}`;

              const dato2 = document.createElement("small");
              dato2.textContent = formattedDate;

              const chatContent = document.createElement("div");
              chatContent.classList.add(
                "flex",
                "flex-col",
                "items-start",
                "mb-2"
              );
              if (pengirim === currentUsername) {
                pesanElement.classList.add(
                  "bg-green-500",
                  "text-white",
                  "rounded-lg",
                  "flex-end",
                  "justify-end",
                  "p-2",
                  "max-w-52"
                );
                dato2.classList.add(
                  "text-black",
                  "p-2",
                );
                chatContent.classList.add("self-end", "items-end", "w-full");
              } else {
                pesanElement.classList.add(
                  "bg-blue-500",
                  "text-white",
                  "rounded-lg",
                  "p-2",
                  "max-w-52"
                );
                dato2.classList.add(
                  "p-2"
                );
                chatContent.classList.add(
                  "self-start",
                  "items-start",
                  "w-full"
                );
              }

              fotoProfil.appendChild(fotoProfilImg);

              chatContent.appendChild(pesanElement);
              chatContent.appendChild(pengirimElement);
              chatContent.appendChild(dato2);

              containerPesan.appendChild(fotoProfil);
              containerPesan.appendChild(chatContent);

              chatMessages.appendChild(containerPesan);
            }
          });

          chatMessages.scrollTop = chatMessages.scrollHeight;
        } else {
          console.error("Terjadi kesalahan saat memuat chat:", xhr.statusText);
        }
      }
    };
    xhr.send();
  }

  loadChat();
  setInterval(loadChat, 1000);
});
