// Toggle password input visibility
function togglePasswordVisibility(fieldId, btnEl) {
    const field = document.getElementById(fieldId);
    const icon = btnEl.querySelector('i');
    if (field && icon) {
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}
window.togglePasswordVisibility = togglePasswordVisibility;

document.addEventListener("DOMContentLoaded", function() {

    // Chatbot Logic
    const chatbotToggler = document.querySelector(".chatbot-toggler");
    if(chatbotToggler) {
        chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
    }

    const chatInput = document.querySelector(".chat-input input");
    const sendChatBtn = document.querySelector(".chat-input button");
    const chatbox = document.querySelector(".chatbox");

    const createChatLi = (message, className) => {
        const chatLi = document.createElement("li");
        chatLi.classList.add("chat", className);
        let chatContent = className === "outgoing" ? `<p></p>` : `<i class="fa-solid fa-robot" style="align-self:flex-end; color:#4361ee; margin-right:5px;"></i><p></p>`;
        chatLi.innerHTML = chatContent;
        chatLi.querySelector("p").textContent = message;
        return chatLi;
    }

    const generateResponse = (incomingChatLi) => {
        const messageElement = incomingChatLi.querySelector("p");
        // Basic static responses for front-end demo
        const responses = [
            "Welcome to the SIMS Portal! How can I help you?",
            "You can find course information on the Courses page.",
            "To apply, please visit the Signup page.",
            "Our support team is available Mon-Fri, 9AM-5PM.",
            "Please check the Contact page for location details."
        ];
        messageElement.textContent = responses[Math.floor(Math.random() * responses.length)];
        chatbox.scrollTo(0, chatbox.scrollHeight);
    }

    const handleChat = () => {
        const userMessage = chatInput.value.trim();
        if(!userMessage) return;

        chatInput.value = "";
        
        chatbox.appendChild(createChatLi(userMessage, "outgoing"));
        chatbox.scrollTo(0, chatbox.scrollHeight);

        setTimeout(() => {
            const incomingChatLi = createChatLi("Typing...", "incoming");
            chatbox.appendChild(incomingChatLi);
            chatbox.scrollTo(0, chatbox.scrollHeight);
            setTimeout(() => {
                generateResponse(incomingChatLi);
            }, 600);
        }, 600);
    }

    if(sendChatBtn) {
        sendChatBtn.addEventListener("click", handleChat);
        chatInput.addEventListener("keydown", (e) => {
            if(e.key === "Enter") handleChat();
        });
    }

    // Feedback Form Word Limit Validation
    const feedbackMsg = document.getElementById("feedbackMsg");
    if(feedbackMsg) {
        feedbackMsg.addEventListener("input", function() {
            let words = this.value.match(/\b[-?(\w+)?]+\b/gi);
            let wordCount = words ? words.length : 0;
            document.getElementById("wordCount").textContent = `${wordCount}/250 words`;
            if (wordCount > 250) {
                // Trim to 250 words
                this.value = words.slice(0, 250).join(" ");
                document.getElementById("wordCount").textContent = `250/250 words (Limit Reached)`;
                document.getElementById("wordCount").style.color = "red";
            } else {
                document.getElementById("wordCount").style.color = "var(--light-text)";
            }
        });
    }

    // Signup Form Validation
    const signupForm = document.getElementById("signupForm");
    if(signupForm) {
        signupForm.addEventListener("submit", function(e) {
            let isValid = true;
            
            // Name validation (no special chars, reasonable length)
            const nameField = document.getElementById("name");
            const nameError = document.getElementById("nameError");
            const nameRegex = /^[A-Za-z\s]{3,50}$/;
            if(!nameRegex.test(nameField.value)) {
                nameError.style.display = "block";
                nameError.textContent = "Name must be 3-50 characters long and contain only letters.";
                isValid = false;
            } else {
                nameError.style.display = "none";
            }

            // Email validation
            const emailField = document.getElementById("email");
            const emailError = document.getElementById("emailError");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(emailError) {
                if(!emailRegex.test(emailField.value)) {
                    emailError.style.display = "block";
                    emailError.textContent = "Please enter a valid email address.";
                    isValid = false;
                } else {
                    emailError.style.display = "none";
                }
            }

            // Password validation (min 8 chars, 1 uppercase, 1 number)
            const pwdField = document.getElementById("password");
            const pwdError = document.getElementById("passwordError");
            const pwdRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
            if(!pwdRegex.test(pwdField.value)) {
                pwdError.style.display = "block";
                pwdError.textContent = "Password must be 8-20 characters, include 1 uppercase and 1 number.";
                isValid = false;
            } else {
                pwdError.style.display = "none";
            }

            // Confirm Password validation
            const confirmPwdField = document.getElementById("confirm_password");
            const confirmPwdError = document.getElementById("confirmPasswordError");
            if (confirmPwdField && confirmPwdError) {
                if (pwdField.value !== confirmPwdField.value) {
                    confirmPwdError.style.display = "block";
                    confirmPwdError.textContent = "Passwords do not match.";
                    isValid = false;
                } else {
                    confirmPwdError.style.display = "none";
                }
            }

            if(!isValid) {
                e.preventDefault();
            }
        });
    }

    // Login Form Validation
    const loginForm = document.getElementById("loginForm");
    if(loginForm) {
        loginForm.addEventListener("submit", function(e) {
            let isValid = true;

            const emailField = document.getElementById("email");
            const emailError = document.getElementById("emailError");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(emailError) {
                if(!emailRegex.test(emailField.value)) {
                    emailError.style.display = "block";
                    emailError.textContent = "Please enter a valid email address.";
                    isValid = false;
                } else {
                    emailError.style.display = "none";
                }
            }

            if(!isValid) {
                e.preventDefault();
            }
        });
    }

    // Search Filtering
    const searchInput = document.getElementById("searchInput");
    if(searchInput) {
        searchInput.addEventListener("input", function() {
            const filter = this.value.toLowerCase();
            const cards = document.querySelectorAll(".grid-container .card");
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if(text.includes(filter)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }
});
