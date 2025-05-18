import { GoogleGenerativeAI } from "@google/generative-ai";

// Initialize the generative model
const genAI = new GoogleGenerativeAI('REMOVED_FOR_SECURITY');
const model = genAI.getGenerativeModel({
  model: "gemini-1.5-flash",
  systemInstruction: "Create a clear, helpful chatbot system that answers user questions about SphereConnect’s mission, vision, services, and team. Provide step-by-step guidance for signing up, event participation, data security, password resets, and organizing events. Highlight personalized support, badges, and feedback options while ensuring clarity and accuracy in responses.",
});

const generationConfig = {
    temperature: 1,
    topP: 0.95,
    topK: 40,
    maxOutputTokens: 8192,
    responseMimeType: "text/plain",
};

const chat = model.startChat({
    generationConfig,
    history: [
      {
        role: "user",
        parts: [{ text: "SphereConnect's Description" }],
      },
      {
        role: "model",
        parts: [{ text: "SphereConnect is an all-in-one platform designed to connect communities, event organizers, and volunteers. Our mission is to empower individuals and communities by offering a simple, user-friendly platform for finding and organizing events that make a real difference." }],
      },
      {
        role: "user",
        parts: [{ text: "Method of earning badges." }],
      },
      {
        role: "model",
        parts: [{ text: "You can earn badges by participating in events. The first badge is awarded after joining your first event, the second badge after five events, and the last badge after ten events." }],
      },
      {
        role: "user",
        parts: [{ text: "SphereConnect's mission and vision." }],
      },
      {
        role: "model",
        parts: [{ text: "The mission is we aim to empower individuals and communities by providing a simple, user-friendly platform for finding and organizing events that make a real difference. Whether you want to volunteer, lead an event, or join a community initiative, SphereConnect is here to help you make a positive impact. \nThe vision is we envision a world where communities come together to support each other, and people of all backgrounds can easily participate in and organize impactful events. Our platform fosters collaboration, promotes civic responsibility, and nurtures community engagement." }],
      },
      {
        role: "user",
        parts: [{ text: "SphereConnect's team members" }],
      },
      {
        role: "model",
        parts: [{ text: "SphereConnect is led by a dedicated team, including Ethan Amir (CEO), Ryan Hakim (CFO), Sarah Liyana (CMO), Emily Nurin (COO), and Hannah Sofia (CTO)." }],
      },
      {
        role: "user",
        parts: [{ text: "Solution of Forgetting Password" }],
      },
      {
        role: "model",
        parts: [{ text: "If you forget your password, click the \"Forgot Password\" link on the login page. You will be prompted to enter your email address, and then you will receive instructions on how to reset your password." }],
      },
      {
        role: "user",
        parts: [{ text: "Method to feedback or report issues" }],
      },
      {
        role: "model",
        parts: [{ text: "After participating in an event or using a service, you can leave a review or rate the experience.  If you encounter any issues, you can report them through the \"Past Events Review\" feature within the profile page or by contacting our support team directly via the \"Contact Us\" page." }],
      },
      {
        role: "user",
        parts: [{ text: "Operation of SphereConnect" }],
      },
      {
        role: "model",
        parts: [{ text: "SphereConnect simplifies event management and participation.  Here's a summary of how it works:\n\n1. **Sign Up and Login:** Create an account to start creating events and connecting with university students and the community.\n2. **Create or Browse Events:**  Create your own event and attract collaborators, or browse existing events to find opportunities to participate or volunteer.\n3. **Get Involved or Manage:**  Join events as a participant or volunteer, or manage your own events.  There are no fees for using the platform to organize events.\n4. **Make an Impact:** Participate in events and contribute to positive change in your community." }],
      },
      {
        role: "user",
        parts: [{ text: "SphereConnect Privacy Policy" }],
      },
      {
        role: "model",
        parts: [{ text: "We protect against unauthorized access, fraud, and abuse of our platform. We monitor suspicious activity to safeguard the integrity of the platform." }],
      },
      {
        role: "user",
        parts: [{ text: "Contact SphereConnect’s support team" }],
      },
      {
        role: "model",
        parts: [{ text: "You can reach SphereConnect through the 'Contact Us' page on our website or email us at support@sphereconnect.com. Our working hours are Monday to Friday, 9:00 AM to 5:00 PM." }],
      },
      {
        role: "user",
        parts: [{ text: "How organizers create events" }],
      },
      {
        role: "model",
        parts: [{ text: "You can visit the \"Events\" page and choose whether you want to create a regular event or an event requiring volunteers. To create a regular event, click \"Participate.\" For events that need volunteers, click \"Volunteer.\" After making your selection, click the \"Create New Event\" button and provide the necessary information." }],
      },
      {
        role: "user",
        parts: [{ text: "Pricing and Event Fees" }],
      },
      {
        role: "model",
        parts: [{ text: "SphereConnect is free to use, though some events may have fees set by third-party organizers." }],
      },
      {
        role: "user",
        parts: [{ text: "Event Updates, Reviews, and Rewards" }],
      },
      {
        role: "model",
        parts: [{ text: "You'll receive updates and reminders about the events or upcoming events volunteer. You'll also have the opportunity to rate and review the event after it's concluded, helping to improve the platform and provide feedback for future events. You may also earn badges depending on your participation level." }],
      },
    ],
});

const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
const chatbox = document.querySelector(".chatbox");
const chatbotToggler = document.querySelector(".chatbot-toggler");
const chatbotCloseBtn = document.querySelector(".close-btn");

let userMessage;
const inputInitHeight = chatInput.scrollHeight;

// Utility to classify the message
const classifyMessage = (message) => {
    // Remove special characters (like ?) and normalize the message
    const normalizedMessage = message.replace(/[^\w\s]/g, "").toLowerCase();
    
    // Define keywords for classification
    const eventKeywords = ["event", "date", "time", "location", "volunteer", "opportunity"];
    
    // Check if any keyword matches the normalized message
    return eventKeywords.some(keyword => normalizedMessage.includes(keyword)) ? "event" : "general";
};

// Function to fetch response from the database if no local match
const fetchDatabaseResponse = async (message) => {
    try {
        const responses = await Promise.all([
            fetch("fetch_events.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ message })
            }).then(response => response.json()),
            fetch("fetch_volunteers.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ message })
            }).then(response => response.json())
        ]);

        const [eventsData, volunteersData] = responses;
        let responseText = "";

        // Randomly select a few events
        if (eventsData.events && eventsData.events.length > 0) {
            const randomEvents = getRandomItems(eventsData.events, 2);
            responseText += "Here are some events I found in SphereConnect:\n\n";
            randomEvents.forEach(event => {
                responseText += `📅 ${event.title}\n`;
                responseText += `📆 Date: ${event.date}\n`;
                responseText += `📍 Location: ${event.location}\n`;
                responseText += `ℹ️ Category: ${event.event_category}\n`;
                responseText += `👥 Maximum Participants: ${event.max_participants}\n`;
                responseText += `📝 Registration Period: ${event.start_registration_date} to ${event.due_registration_date}\n`;
                responseText += `📄 Description: ${event.description}\n\n`;
            });
        }

        // Randomly select a few volunteer opportunities
        if (volunteersData.volunteers && volunteersData.volunteers.length > 0) {
            const randomVolunteers = getRandomItems(volunteersData.volunteers, 2);
            responseText += "Here are some volunteer opportunities I found in SphereConnect:\n\n";
            randomVolunteers.forEach(volunteer => {
                responseText += `🤝 ${volunteer.title}\n`;
                responseText += `📆 Date: ${volunteer.date}\n`;
                responseText += `📍 Location: ${volunteer.location}\n`;
                responseText += `👥 Maximum Volunteers: ${volunteer.max_volunteer}\n`;
                responseText += `📄 Description: ${volunteer.description}\n`;
                responseText += `✅ Requirements: ${volunteer.requirement}\n\n`;
            });
        }

        return responseText || "I'm sorry, I couldn't find any information on that topic in SphereConnect.";
    } catch (error) {
        console.error('Error fetching data:', error);
        return "I'm sorry, there was an error retrieving the information. Please try again later.";
    }
};


// Helper function to get random items from an array
function getRandomItems(array, numItems) {
    const shuffled = array.sort(() => 0.5 - Math.random());
    return shuffled.slice(0, numItems);
}

// The cute people in chatbot
const createChatLi = (message, className) => {
    const chatLi = document.createElement("li");
    chatLi.classList.add("chat", className);
    let chatContent = className === "outgoing" ? `<p></p>` : `<span class="material-symbols-outlined">smart_toy</span><p></p>`;
    
    chatLi.innerHTML = chatContent;
    chatLi.querySelector("p").textContent = message;
    return chatLi;
}

// Generate Response with AI and retry on failure
const generateResponse = async (chatElement) => {
    const messageElement = chatElement.querySelector("p");

    try {
        if (classifyMessage(userMessage) === "event") {
            const dbResponse = await fetchDatabaseResponse(userMessage);
            messageElement.textContent = dbResponse;
        } else {
            const result = await chat.sendMessageStream(userMessage);
            let aiResponse = "";
            for await (const chunk of result.stream) {
                aiResponse += chunk.text();
            }
            // Replace markdown-style formatting
            aiResponse = aiResponse.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>").replace(/\*(.*?)\*/g, "<em>$1</em>");
            messageElement.innerHTML = aiResponse;
        }
    } catch (error) {
        console.error("Error generating response: ", error);
        // return "Sorry, I couldn't process your request.";
        messageElement.textContent = "You have reach your limitation for today. You can reach us by filling in the form in Contact Us page.";
    }
    chatbox.scrollTo(0, chatbox.scrollHeight);
};

// Handle chat
const handleChat = async () => {
    userMessage = chatInput.value.trim();
    if(!userMessage) return;
    chatInput.value = "";
    chatInput.style.height = `${inputInitHeight}px`;

    chatbox.appendChild(createChatLi(userMessage, "outgoing"));
    chatbox.scrollTo(0, chatbox.scrollHeight);
    setTimeout(async () => {
        const incomingChatLi = createChatLi("", "incoming");
        const thinkingIndicator = document.createElement("div");
        thinkingIndicator.classList.add("thinking-indicator");
        thinkingIndicator.innerHTML = `
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        `;
        incomingChatLi.querySelector("p").appendChild(thinkingIndicator);
        chatbox.appendChild(incomingChatLi);
        chatbox.scrollTo(0, chatbox.scrollHeight);
        generateResponse(incomingChatLi);
    }, 200);
}

// Chat Input
chatInput.addEventListener("input", () => {
    //Adjust height of the input textarea based on its content
    chatInput.style.height = `${inputInitHeight}px`;
    chatInput.style.height = `${chatInput.scrollHeight}px`;
});

// Enter to send message using keyboard
chatInput.addEventListener("keydown", (e) => {
    //If enter key is pressed without shift key and the window width is greater than 800px, handle the chat
    if(e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleChat();
    }
    //Adjust height of the input textarea based on its content
    chatInput.style.height = `${inputInitHeight}px`;
    chatInput.style.height = `${chatInput.scrollHeight}px`;
});

// send chat button
sendChatBtn.addEventListener("click", handleChat);
//close btn for responsive design
chatbotCloseBtn.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
//close btn for web page
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
