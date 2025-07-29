// // AJAX => SUPPRIMER LE COMMENTAIRE HTML EN DIRECT SANS RECHARGER LA PAGE

// document.addEventListener('DOMContentLoaded', () => {
//     document.querySelectorAll(".delete-comment-form").forEach((form) => {
//         form.addEventListener("submit", async function (e) {
//             e.preventDefault();

//             const commentId = this.dataset.commentId;
//             const url = this.action;
//             const token = this.querySelector('input[name="_token"]').value;

//             const response = await fetch(url, {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/x-www-form-urlencoded",
//                     "X-Requested-With": "XMLHttpRequest",
//                 },
//                 body: new URLSearchParams({ _token: token }),
//             });

//             if (response.ok) {
//                 // Supprimer l'élément du DOM
//                 const commentElement = document.getElementById(commentId);
//                 if (commentElement) {
//                     commentElement.remove();
//                 }

//                 const tweetId = this.dataset.tweetId;
//                 const countElement = document.getElementById(`comment-count-${tweetId}`);

//                 if (countElement) {
//                     const match = countElement.textContent.trim().match(/^\d+/);
//                     const currentCount = match ? parseInt(match[0]) : 0;
//                     const newCount = currentCount - 1;
//                     countElement.textContent = `${newCount} commentaires`;
//                 }

//             } else {
//                 alert("Erreur lors de la suppression du commentaire.");
//             }
//         });
//     });
// });
