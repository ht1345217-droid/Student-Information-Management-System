import { useState } from 'react';
import { MessageSquare, X, Send } from 'lucide-react';

const Chatbot = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState([
    { text: "Hello! I'm the SIMS AI assistant. How can I help you today?", isBot: true }
  ]);
  const [input, setInput] = useState('');

  const handleSend = (e) => {
    e.preventDefault();
    if (!input.trim()) return;

    setMessages([...messages, { text: input, isBot: false }]);
    const userMessage = input.toLowerCase();
    setInput('');

    // Mock AI response
    setTimeout(() => {
      let botResponse = "I'm sorry, I didn't understand that. Please contact support.";
      if (userMessage.includes('admission') || userMessage.includes('apply')) {
        botResponse = "You can apply for admissions through the signup portal. Deadlines are approaching!";
      } else if (userMessage.includes('fee') || userMessage.includes('pay')) {
        botResponse = "Fees can be paid from your student dashboard under the 'Transactions' tab.";
      } else if (userMessage.includes('password')) {
        botResponse = "You can reset your password using the 'Forgot Password' link on the login page.";
      }
      setMessages(prev => [...prev, { text: botResponse, isBot: true }]);
    }, 1000);
  };

  return (
    <div className="fixed bottom-6 right-6 z-50">
      {isOpen ? (
        <div className="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-dark-700 w-80 h-96 flex flex-col overflow-hidden animate-in slide-in-from-bottom-5">
          <div className="bg-primary-600 text-white p-4 flex justify-between items-center">
            <h3 className="font-semibold flex items-center"><MessageSquare className="w-5 h-5 mr-2"/> SIMS Assistant</h3>
            <button onClick={() => setIsOpen(false)} className="hover:text-gray-200"><X className="w-5 h-5"/></button>
          </div>
          
          <div className="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50 dark:bg-dark-900">
            {messages.map((msg, i) => (
              <div key={i} className={`flex ${msg.isBot ? 'justify-start' : 'justify-end'}`}>
                <div className={`max-w-[80%] p-3 rounded-2xl text-sm ${msg.isBot ? 'bg-white dark:bg-dark-800 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-dark-700 rounded-tl-none' : 'bg-primary-600 text-white rounded-tr-none'}`}>
                  {msg.text}
                </div>
              </div>
            ))}
          </div>
          
          <form onSubmit={handleSend} className="p-3 bg-white dark:bg-dark-800 border-t border-gray-200 dark:border-dark-700 flex items-center">
            <input 
              type="text" 
              value={input}
              onChange={(e) => setInput(e.target.value)}
              placeholder="Type a message..." 
              className="flex-1 bg-gray-100 dark:bg-dark-900 text-gray-800 dark:text-gray-200 rounded-full px-4 py-2 outline-none focus:ring-2 focus:ring-primary-500 text-sm"
            />
            <button type="submit" className="ml-2 p-2 bg-primary-600 text-white rounded-full hover:bg-primary-700 transition">
              <Send className="w-4 h-4"/>
            </button>
          </form>
        </div>
      ) : (
        <button 
          onClick={() => setIsOpen(true)}
          className="bg-primary-600 hover:bg-primary-700 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1"
          aria-label="Open chat"
        >
          <MessageSquare className="w-6 h-6" />
        </button>
      )}
    </div>
  );
};

export default Chatbot;
