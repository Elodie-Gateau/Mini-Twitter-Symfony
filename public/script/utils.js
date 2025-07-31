// AJAX => LIKER SANS RECHARGER LA PAGE

document.addEventListener("DOMContentLoaded", () => {
    const likeSound = new Audio('../audio/chien.wav'); // Remplacez par le vrai chemin de votre fichier audio

    document.querySelectorAll(".like-form").forEach((form) => {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData(form);        
            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            })
                .then((res) => {
                    if (!res.ok) throw new Error("Erreur serveur");
                    return res.json();
                })
                .then((data) => {
                    const counter = document.querySelector(
                        `#like-count-${data.tweetId}`
                    );
                    if (counter) {
                        counter.textContent = data.likes;
                    }
            
                likeSound.play().catch(error => {
                    // Gérer les erreurs de lecture (ex: l'utilisateur n'a pas encore interagi avec la page)
                    console.warn("Impossible de jouer le son:", error);
                });
                })
                .catch(() => {
                    alert("Une erreur est survenue lors du like.");
                });
        });
    });
});

// AJAX => Afficher les commentaires sans recharger la page

document.querySelectorAll(".load-comments").forEach((button) => {
    button.addEventListener("click", async () => {
        const tweetId = button.dataset.tweetId;
        const commentsContainer = document.querySelector(
            `#comments-container-${tweetId}`
        );

        // Toggle : si déjà visible → on vide et on quitte
        if (commentsContainer.dataset.loaded === "true") {
            commentsContainer.innerHTML = "";
            commentsContainer.dataset.loaded = "false";
            return;
        }

        try {
            const res = await fetch(`/tweet/${tweetId}/comments`, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            if (!res.ok) throw new Error("Erreur serveur");
            const html = await res.text();
            commentsContainer.innerHTML = html;
            commentsContainer.dataset.loaded = "true";
        } catch (e) {
            console.error(e);
            commentsContainer.innerHTML =
                "<p class='text-red-500'>Erreur lors du chargement.</p>";
        }
    });
});

// AJAX => Ajouter un commentaire et recharger la liste
document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener("submit", async (e) => {
        // Vérifie si c'est le formulaire d'ajout de commentaire
        if (e.target && e.target.classList.contains("add-comment-form")) {
            e.preventDefault();

            const form = e.target;
            const tweetId = form.dataset.tweetId;
            const formData = new FormData(form);
            const commentsContainer = document.querySelector(
                `#comments-container-${tweetId}`
            );

            try {
                const res = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                });

                if (!res.ok) throw new Error("Erreur serveur");

                // Récupère le HTML des commentaires mis à jour
                const html = await res.text();
                commentsContainer.innerHTML = html;

                // Marque la liste comme chargée
                commentsContainer.dataset.loaded = "true";
            } catch (error) {
                console.error(error);
                alert(
                    "Une erreur est survenue lors de l'ajout du commentaire."
                );
            }
        }
    });
});

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
