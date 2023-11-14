document.addEventListener("DOMContentLoaded", function () {
    fetchProfileInfo();
    const editForm = document.getElementById("editForm");
    if (editForm) {
        editForm.addEventListener("submit", function (event) {
            event.preventDefault();
            submitEditForm();
        });
    }
});
function fetchProfileInfo() { 
    const userId = 1; 
    fetch(`/api/profile/${userId}`)
        .then(response => response.json())
        .then(data => {
            const profileInfo = document.getElementById("profile-info");
            if (profileInfo) {
                profileInfo.innerHTML = `
                    <p><strong>Username:</strong> ${data.username}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching profile information:', error);
        });
}
function showEditForm() {
    const profileInfo = document.getElementById("profile-info");
    const editForm = document.getElementById("edit-profile-form");
    if (profileInfo && editForm) {
        profileInfo.style.display = "none";
        editForm.style.display = "block";
    }
}
function submitEditForm() {
    const editForm = document.getElementById("editForm");
    if (editForm) {
        const formData = new FormData(editForm);
        fetch("/api/profile/update", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchProfileInfo();
                showEditForm();
            } else {
                console.error('Update failed:', data.message);
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
        });
    }
}
