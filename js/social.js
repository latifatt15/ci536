
  const modal = document.getElementById("messageModal");
  const recipientField = document.getElementById("recipientField");
  const closeBtn = document.querySelector(".close-btn");

  document.querySelectorAll(".message-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const recipient = btn.getAttribute("data-recipient");
      recipientField.value = recipient;
      modal.style.display = "block";
    });
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target == modal) {
      modal.style.display = "none";
    }
  });
  
  document.getElementById("inboxBtn").addEventListener("click", () => {
  fetch("load_messages.php")
    .then(response => response.text())
    .then(html => {
      document.getElementById("messageList").innerHTML = html;
      document.getElementById("inboxModal").style.display = "block";
    });
});

document.querySelector(".close-btn").addEventListener("click", () => {
  document.getElementById("inboxModal").style.display = "none";
});

// Close modal if clicking outside
window.addEventListener("click", (e) => {
  const modal = document.getElementById("inboxModal");
  if (e.target === modal) {
    modal.style.display = "none";
  }
});

// Handle reply forms dynamically
document.addEventListener("submit", function (e) {
  if (e.target.classList.contains("reply-form")) {
    e.preventDefault();
    const formData = new FormData(e.target);
    fetch("send_reply.php", {
      method: "POST",
      body: formData
    }).then(() => {
      alert("Reply sent!");
      e.target.reset();
    });
  }
});


