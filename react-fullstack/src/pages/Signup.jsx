import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { BookOpen, AlertCircle, CheckCircle, Eye, EyeOff } from 'lucide-react';
import { supabase } from '../lib/supabase';

const Signup = () => {
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    confirmPassword: ''
  });
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();

  // Name validation: letters only, max 50 chars
  const validateName = (name) => {
    return /^[A-Za-z\s]{1,50}$/.test(name);
  };

  // Password strength validation
  const validatePassword = (pass) => {
    return pass.length >= 8 && /[A-Z]/.test(pass) && /[0-9]/.test(pass);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (!validateName(formData.firstName) || !validateName(formData.lastName)) {
      return setError('Name fields must contain only letters and be less than 50 characters.');
    }
    if (formData.password !== formData.confirmPassword) {
      return setError('Passwords do not match.');
    }
    if (!validatePassword(formData.password)) {
      return setError('Password must be at least 8 characters, with one uppercase letter and one number.');
    }

    setIsLoading(true);
    
    // Using Supabase Auth
    try {
      const { data, error: signupError } = await supabase.auth.signUp({
        email: formData.email,
        password: formData.password,
        options: {
          data: {
            first_name: formData.firstName,
            last_name: formData.lastName,
            role: 'student' // default role
          }
        }
      });

      if (signupError) throw signupError;
      
      alert('Signup successful! In a real app, you would check your email for a verification link. Since this is a demo, please login using student@sims.edu or admin@sims.edu');
      navigate('/login');
    } catch (err) {
      setError(err.message || 'An error occurred during signup');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-[calc(100vh-4rem)] flex items-center justify-center relative py-12 px-4 sm:px-6 lg:px-8">
      {/* Background Image */}
      <div className="absolute inset-0 z-0">
        <img 
          src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" 
          alt="Campus" 
          className="w-full h-full object-cover filter blur-[2px] brightness-50 dark:brightness-[0.25]"
        />
      </div>

      <div className="max-w-md w-full space-y-8 glass-panel bg-white/80 dark:bg-dark-900/80 p-10 rounded-3xl shadow-2xl relative z-10 border border-white/20">
        <div className="text-center">
          <BookOpen className="mx-auto h-12 w-12 text-primary-600" />
          <h2 className="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">Create an Account</h2>
        </div>
        
        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          {error && (
            <div className="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-3 rounded-lg flex items-center text-sm">
              <AlertCircle className="h-5 w-5 mr-2" />
              {error}
            </div>
          )}
          
          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
              <input
                type="text"
                required
                maxLength={50}
                className="input-field"
                value={formData.firstName}
                onChange={(e) => setFormData({...formData, firstName: e.target.value})}
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
              <input
                type="text"
                required
                maxLength={50}
                className="input-field"
                value={formData.lastName}
                onChange={(e) => setFormData({...formData, lastName: e.target.value})}
              />
            </div>
          </div>
          
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email address</label>
            <input
              type="email"
              required
              className="input-field"
              value={formData.email}
              onChange={(e) => setFormData({...formData, email: e.target.value})}
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <div className="relative">
              <input
                type={showPassword ? "text" : "password"}
                required
                className="input-field pr-10"
                value={formData.password}
                onChange={(e) => setFormData({...formData, password: e.target.value})}
              />
              <button
                type="button"
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                onClick={() => setShowPassword(!showPassword)}
                aria-label="Toggle password visibility"
              >
                {showPassword ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
              </button>
            </div>
            <p className="text-xs text-gray-500 mt-1">Min 8 chars, 1 uppercase, 1 number.</p>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
            <div className="relative">
              <input
                type={showConfirmPassword ? "text" : "password"}
                required
                className="input-field pr-10"
                value={formData.confirmPassword}
                onChange={(e) => setFormData({...formData, confirmPassword: e.target.value})}
              />
              <button
                type="button"
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                aria-label="Toggle password visibility"
              >
                {showConfirmPassword ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
              </button>
            </div>
          </div>

          {/* Fake Recaptcha for UI */}
          <div className="border border-gray-300 dark:border-dark-700 rounded p-4 flex items-center justify-between bg-gray-50 dark:bg-dark-800">
            <div className="flex items-center">
              <input type="checkbox" required className="h-5 w-5 text-primary-600 focus:ring-primary-500 rounded border-gray-300" />
              <span className="ml-2 text-sm text-gray-700 dark:text-gray-300">I'm not a robot</span>
            </div>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/RecaptchaLogo.svg/1200px-RecaptchaLogo.svg.png" alt="reCAPTCHA" className="h-8 opacity-50" />
          </div>

          <div>
            <button type="submit" disabled={isLoading} className="btn-primary w-full flex justify-center py-3">
              {isLoading ? 'Creating account...' : 'Sign up'}
            </button>
          </div>
        </form>
        
        <p className="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
          Already have an account? <Link to="/login" className="font-medium text-primary-600 hover:text-primary-500">Log in</Link>
        </p>
      </div>
    </div>
  );
};

export default Signup;
