
function togglePasswordForms(formAppear = null, formeDsappear) {
    const formToHide = document.getElementById(formeDsappear);
    const formToShow = document.getElementById(formAppear);
    if(formToHide){
        formToHide.classList.toggle('active');
    }
    if (formToShow) {
        formToShow.classList.toggle('active');
    }
}




// function changeImaage(){
//     document.addEventListener("DOMContentLoaded", () => {
//         const profilePic = document.getElementById('profilePic');
//         const changePhotoText = document.querySelector('.change-photo-text');
    
//         // Show the text on hover
//         profilePic.addEventListener('mouseenter', () => {
//             changePhotoText.style.display = 'block';
//         });
    
//         // Hide the text when not hovering
//         profilePic.addEventListener('mouseleave', () => {
//             changePhotoText.style.display = 'none';
//         });
    
        
//         profilePic.addEventListener('mousemove', (e) => {
//             const rect = profilePic.getBoundingClientRect();
//             const x = e.clientX - rect.left;
//             const y = e.clientY - rect.top;
    
//             changePhotoText.style.left = x + 'px';
//             changePhotoText.style.top = y + 'px';
//         });
//     });
// }
// changeImage();
// function showError(message) {
//     const errorDiv = document.getElementById('errorMessage');
//     errorDiv.textContent = message;
//     errorDiv.style.display = 'block';
    
//     // Hide error after 3 seconds
//     // setTimeout(() => {
//     //     errorDiv.style.display = 'none';
//     // }, 10000);
// }

// document.addEventListener("DOMContentLoaded",() =>{
//     document.getElementById('passwordChangeForm').addEventListener('submit', function (event) {
//         event.preventDefault();
//         const oldPassword = document.getElementById('old-password').value;
//         const newPassword = document.getElementById('new-password').value;
//         const confirmPassword = document.getElementById('confirm-password').value;

//         // ADD A COREN PASSWORD CHICKER
//         if (newPassword !== confirmPassword) {
//             showError('Passwords do not match!');
//             return;
//         }
        
//         if (newPassword === oldPassword) {
//             showError('New password must be different from the old password!');
//             return;
//         }
        
//         if (newPassword.length < 8) {
//             showError('Password must be at least 8 characters long!');
//             return;
//         }
        
//         this.submit();
        
//     })
// })
