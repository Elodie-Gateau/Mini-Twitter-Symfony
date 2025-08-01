// AJAX => LIKER SANS RECHARGER LA PAGE

document.addEventListener("DOMContentLoaded", () => {

    const likeSound = new Audio('/audio/chien.wav');

    const handleLike = async (form) => {
        // Crée un objet FormData à partir du formulaire pour envoyer les données.
        const formData = new FormData(form);

        // Récupère le type d'entité ('tweet' ou 'comment') et son ID depuis les attributs de données du formulaire.
        const type = form.dataset.type;
        const id = form.dataset.id;

        try {
            // Envoie une requête POST au serveur sans recharger la page.
            const res = await fetch(form.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });

            if (!res.ok) {
                throw new Error("Erreur serveur");
            }

            const data = await res.json();

            const counter = document.querySelector(`#like-count-${id}`);

            if (counter) {
                counter.textContent = data.likes;
            }

            likeSound.play().catch(error => {
                console.warn("Impossible de jouer le son:", error);
            });

        } catch (error) {
            // Ce bloc gère toutes les erreurs, qu'elles proviennent de la requête fetch ou d'autres opérations.
            alert("Une erreur est survenue lors du like.");
            console.error("Erreur lors du like:", error);
        }
    };

    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            handleLike(form);
        });
    });

    // // ---------------------------------------------
    // FENETRE MODALE DE SUPPRESSION
    // // ---------------------------------------------
    const deleteButtons = document.querySelectorAll('.delete-tweet-button'); // Ajoutez une classe à votre bouton de suppression
    const modal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const modalMessage = document.getElementById('modalMessage');
    let formToSubmit = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Empêche la soumission de formulaire par défaut
            formToSubmit = this.closest('form'); // Récupère le formulaire parent

            // Récupérer les données du bouton cliqué
            const userId = this.dataset.userId; // Accède à data-tweet-id
            const tweetId = this.dataset.tweetId; // Accède à data-tweet-id
            const reportedTweetId = this.dataset.reportedTweetId;
            const commentId = this.dataset.commentId; // Accède à data-tweet-id
            const reportedCommentId = this.dataset.reportedCommentId;
            const deleteUserId = this.dataset.deleteUserId;

            // Personnalise le message de la modale en fonction des données disponibles

            if (userId) {
                // Il s'agit d'une suppression de tweet signalé
                let message = `Êtes-vous sûr de vouloir supprimer cet utilisateur`;
                // if (tweetContent) {
                // Si le contenu est disponible, l'ajoute au message
                //     message += ` avec le contenu : "${tweetContent}"`;
                // }
                message += ` ? Cette action est irréversible.`;
                modalMessage.textContent = message;
            } else if (reportedTweetId) {
                // Il s'agit d'une suppression de tweet signalé
                let message = `Êtes-vous sûr de vouloir supprimer ce tweet signalé`;
                // if (tweetContent) {
                // Si le contenu est disponible, l'ajoute au message
                //     message += ` avec le contenu : "${tweetContent}"`;
                // }
                message += ` ? Cette action est irréversible.`;
                modalMessage.textContent = message;
            } else if (tweetId) {
                // Il s'agit d'une suppression de tweet normal (si vous avez un tel bouton ailleurs)
                let message = `Êtes-vous sûr de vouloir supprimer le tweet`;
                message += ` ? Cette action est irréversible.`;
                modalMessage.textContent = message;
            } else if (reportedCommentId) {
                // Il s'agit d'une suppression de tweet signalé
                let message = `Êtes-vous sûr de vouloir supprimer ce commentaire signalé`;
                // if (tweetContent) {
                // Si le contenu est disponible, l'ajoute au message
                //     message += ` avec le contenu : "${tweetContent}"`;
                // }
                message += ` ? Cette action est irréversible.`;
                modalMessage.textContent = message;
            } else if (commentId) {
                // Il s'agit d'une suppression de tweet normal (si vous avez un tel bouton ailleurs)
                let message = `Êtes-vous sûr de vouloir supprimer le commentaire`;
                message += ` ? Cette action est irréversible.`;
                modalMessage.textContent = message;
            } else if (deleteUserId) {
                // Il s'agit d'une suppression de tweet normal (si vous avez un tel bouton ailleurs)
                let message = `Êtes-vous sûr de vouloir supprimer le compte`;
                // message += ` ? Cette action est irréversible.`;
                modalMessage.textContent = message;
            }else {
                // Message de secours si aucun ID spécifique n'est trouvé
                modalMessage.textContent = `Êtes-vous sûr de vouloir supprimer ? Cette action est irréversible.`;
            }

            modal.classList.remove('hidden'); // Affiche la modale
        });
    });

    confirmDeleteBtn.addEventListener('click', function () {
        if (formToSubmit) {
            formToSubmit.submit(); // Soumet le formulaire
        }
        modal.classList.add('hidden'); // Masque la modale
    });

    cancelDeleteBtn.addEventListener('click', function () {
        modal.classList.add('hidden'); // Masque la modale
        formToSubmit = null; // Efface la référence du formulaire
    });

    // // ---------------------------------------------
    // FENETRE MODALE DE SIGNALEMENT
    // // ---------------------------------------------
    const reportButtons = document.querySelectorAll('.report-button'); // Ajoutez une classe à votre bouton de suppression
    const modalReport = document.getElementById('reportConfirmationModal');
    const confirmReportBtn = document.getElementById('confirmReportBtn');
    const cancelReportBtn = document.getElementById('cancelReportBtn');
    const modalTitleReport = document.getElementById('modalTitleReport');
    const modalMessageReport = document.getElementById('modalMessageReport');
    let formToSubmitReport = null;

    reportButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Empêche la soumission de formulaire par défaut
            formToSubmitReport = this.closest('form'); // Récupère le formulaire parent

            // Récupérer les données du bouton cliqué
            const reportTweetId = this.dataset.reportTweetId;
            const reportCommentId = this.dataset.reportCommentId;

            // Personnalise le message de la modale en fonction des données disponibles
            if (reportTweetId) {
                // Il s'agit d'une suppression de tweet signalé
                let message = `Êtes-vous sûr de vouloir signaler ce tweet`;
                 let title = 'Confirmer le signalement'
                 let button = 'Signaler'
                // if (tweetContent) {
                // Si le contenu est disponible, l'ajoute au message
                //     message += ` avec le contenu : "${tweetContent}"`;
                // }
                message += ` ? Cette action est irréversible.`;
                modalMessageReport.textContent = message;
                modalTitleReport.textContent = title;
                confirmReportBtn.textContent = button;
            } else if (reportCommentId) {
                 let message = `Êtes-vous sûr de vouloir signaler ce commentaire`;
                 let title = 'Confirmer le signalement'
                 let button = 'Signaler'
                // if (tweetContent) {
                // Si le contenu est disponible, l'ajoute au message
                //     message += ` avec le contenu : "${tweetContent}"`;
                // }
                message += ` ? Cette action est irréversible.`;
                modalMessageReport.textContent = message;
                modalTitleReport.textContent = title;
                confirmReportBtn.textContent = button;
            } else {
                // Message de secours si aucun ID spécifique n'est trouvé
                modalMessageReport.textContent = `Êtes-vous sûr de vouloir dé-signaler ? Cette action est irréversible.`;
            }

            modalReport.classList.remove('hidden'); // Affiche la modale
        });
    });

    confirmReportBtn.addEventListener('click', function () {
        if (formToSubmitReport) {
            formToSubmitReport.submit(); // Soumet le formulaire
        }
        modalReport.classList.add('hidden'); // Masque la modale
    });

    cancelReportBtn.addEventListener('click', function () {
        modalReport.classList.add('hidden'); // Masque la modale
        formToSubmitReport = null; // Efface la référence du formulaire
    });

    // ---------------------------------------------
    // LOGIQUE POUR LA MODALE DE DÉ-SIGNALEMENT (AVEC LIEN)
    // ---------------------------------------------
    const unreportLinkButtons = document.querySelectorAll('.unreport-link-button');
    const unreportModal = document.getElementById('unreportConfirmationModal');
    const confirmUnreportBtn = document.getElementById('confirmUnreportBtn');
    const cancelUnreportBtn = document.getElementById('cancelUnreportBtn');
    const unreportModalMessage = document.getElementById('unreportModalMessage');
    let unreportUrlToRedirect = null; // Variable pour stocker l'URL du lien

    unreportLinkButtons.forEach(link => { // Maintenant, on itère sur les liens <a>
        link.addEventListener('click', function (event) {
            event.preventDefault(); // TRÈS IMPORTANT : Empêche la redirection immédiate du lien

            unreportUrlToRedirect = this.dataset.unreportUrl; // Récupère l'URL complète du lien
            const unreportTweetId = this.dataset.unreportTweetId; // Récupère l'ID pour le message
            const unreportCommentId = this.dataset.unreportCommentId; // Récupère l'ID pour le message

            if (unreportTweetId) {
            let message = `Êtes-vous sûr de vouloir retirer le signalement du tweet ?`;
            unreportModalMessage.textContent = message;
            }else if (unreportCommentId) {
             let message = `Êtes-vous sûr de vouloir retirer le signalement du commentaire ?`;
            unreportModalMessage.textContent = message;
            }else{   
            unreportModalMessage.textContent = `Êtes-vous sûr de vouloir dé-signaler ?`;          
            }
            unreportModal.classList.remove('hidden'); // Affiche la modale de dé-signalement
        });
    });

    confirmUnreportBtn.addEventListener('click', function () {
        if (unreportUrlToRedirect) {
            window.location.href = unreportUrlToRedirect; // Redirige vers l'URL du lien
        }
        unreportModal.classList.add('hidden');
    });

    cancelUnreportBtn.addEventListener('click', function () {
        unreportModal.classList.add('hidden');
        unreportUrlToRedirect = null; // Efface l'URL
    });

});




// AJAX => Afficher les commentaires sans recharger la page

// document.querySelectorAll(".load-comments").forEach((button) => {
//     button.addEventListener("click", async () => {
//         const tweetId = button.dataset.tweetId;
//         const commentsContainer = document.querySelector(
//             `#comments-container-${tweetId}`
//         );

//         // Toggle : si déjà visible → on vide et on quitte
//         if (commentsContainer.dataset.loaded === "true") {
//             commentsContainer.innerHTML = "";
//             commentsContainer.dataset.loaded = "false";
//             return;
//         }

//         try {
//             const res = await fetch(`/tweet/${tweetId}/comments`, {
//                 headers: { "X-Requested-With": "XMLHttpRequest" },
//             });
//             if (!res.ok) throw new Error("Erreur serveur");
//             const html = await res.text();
//             commentsContainer.innerHTML = html;
//             commentsContainer.dataset.loaded = "true";
//         } catch (e) {
//             console.error(e);
//             commentsContainer.innerHTML =
//                 "<p class='text-red-500'>Erreur lors du chargement.</p>";
//         }
//     });
// });

// AJAX => Ajouter un commentaire et recharger la liste
// document.addEventListener("DOMContentLoaded", () => {
//     document.addEventListener("submit", async (e) => {
//         // Vérifie si c'est le formulaire d'ajout de commentaire
//         if (e.target && e.target.classList.contains("add-comment-form")) {
//             e.preventDefault();

//             const form = e.target;
//             const tweetId = form.dataset.tweetId;
//             const formData = new FormData(form);
//             const commentsContainer = document.querySelector(
//                 `#comments-container-${tweetId}`
//             );

//             try {
//                 const res = await fetch(form.action, {
//                     method: "POST",
//                     body: formData,
//                     headers: { "X-Requested-With": "XMLHttpRequest" },
//                 });

//                 if (!res.ok) throw new Error("Erreur serveur");

//                 // Récupère le HTML des commentaires mis à jour
//                 const html = await res.text();
//                 commentsContainer.innerHTML = html;

//                 // Marque la liste comme chargée
//                 commentsContainer.dataset.loaded = "true";
//             } catch (error) {
//                 console.error(error);
//                 alert(
//                     "Une erreur est survenue lors de l'ajout du commentaire."
//                 );
//             }
//         }
//     });
// });

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
