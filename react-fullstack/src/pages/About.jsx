import { Building2, Award, Clock, Globe, Shield, Users } from 'lucide-react';

const About = () => {
  return (
    <div className="bg-gray-50 dark:bg-dark-900 min-h-screen pb-20">
      {/* Hero Section */}
      <section className="relative bg-primary-900 py-24 sm:py-32 overflow-hidden">
        <div className="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        <div className="absolute inset-0 bg-gradient-to-t from-primary-900/80 to-transparent"></div>
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-in fade-in slide-in-from-bottom-8 duration-1000">
          <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6">About SIMS</h1>
          <p className="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
            Dedicated to providing a seamless educational management experience, bridging the gap between students, faculty, and administration globally.
          </p>
        </div>
      </section>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
        <div className="glass-panel p-8 sm:p-12 rounded-3xl grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
          <div>
            <img 
              src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
              alt="Campus" 
              className="rounded-2xl shadow-2xl"
            />
          </div>
          <div className="space-y-6">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white">Our Mission</h2>
            <p className="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
              We strive to empower educational institutions with cutting-edge technology that simplifies administrative workflows, enhances student engagement, and fosters a collaborative learning environment.
            </p>
            <ul className="space-y-4">
              <li className="flex items-start">
                <Building2 className="h-6 w-6 text-primary-600 mr-3 mt-1" />
                <div>
                  <h4 className="font-semibold text-gray-900 dark:text-white">Modern Infrastructure</h4>
                  <p className="text-gray-600 dark:text-gray-400 text-sm">State-of-the-art digital campus solutions.</p>
                </div>
              </li>
              <li className="flex items-start">
                <Award className="h-6 w-6 text-primary-600 mr-3 mt-1" />
                <div>
                  <h4 className="font-semibold text-gray-900 dark:text-white">Excellence in Service</h4>
                  <p className="text-gray-600 dark:text-gray-400 text-sm">Award-winning support and reliable uptime.</p>
                </div>
              </li>
              <li className="flex items-start">
                <Clock className="h-6 w-6 text-primary-600 mr-3 mt-1" />
                <div>
                  <h4 className="font-semibold text-gray-900 dark:text-white">24/7 Accessibility</h4>
                  <p className="text-gray-600 dark:text-gray-400 text-sm">Access your information anytime, anywhere.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>

        {/* Core Values Section */}
        <div className="mb-24">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Our Core Values</h2>
            <p className="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">The principles that guide our platform and our commitment to educational excellence.</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-white dark:bg-dark-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 text-center hover:shadow-lg transition-shadow">
              <div className="mx-auto bg-blue-50 dark:bg-blue-900/20 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                <Shield className="h-8 w-8 text-blue-600" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">Integrity & Security</h3>
              <p className="text-gray-600 dark:text-gray-400">Protecting student data with enterprise-grade security and transparent practices.</p>
            </div>
            <div className="bg-white dark:bg-dark-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 text-center hover:shadow-lg transition-shadow">
              <div className="mx-auto bg-green-50 dark:bg-green-900/20 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                <Globe className="h-8 w-8 text-green-600" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">Global Accessibility</h3>
              <p className="text-gray-600 dark:text-gray-400">Ensuring education management is accessible from anywhere in the world, on any device.</p>
            </div>
            <div className="bg-white dark:bg-dark-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-700 text-center hover:shadow-lg transition-shadow">
              <div className="mx-auto bg-purple-50 dark:bg-purple-900/20 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                <Users className="h-8 w-8 text-purple-600" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">Community First</h3>
              <p className="text-gray-600 dark:text-gray-400">Building features that foster collaboration between students, teachers, and parents.</p>
            </div>
          </div>
        </div>

        {/* Leadership Team */}
        <div>
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Leadership Team</h2>
            <p className="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Meet the visionaries behind the SIMS platform.</p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {[
              { name: 'Sarah Jenkins', role: 'Chief Executive Officer', img: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
              { name: 'David Chen', role: 'Chief Technology Officer', img: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
              { name: 'Elena Rodriguez', role: 'Head of Education', img: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
              { name: 'Michael Chang', role: 'Lead Product Designer', img: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }
            ].map((member, idx) => (
              <div key={idx} className="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                <img src={member.img} alt={member.name} className="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500" />
                <div className="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent opacity-80"></div>
                <div className="absolute bottom-0 left-0 right-0 p-6 text-left transform translate-y-2 group-hover:translate-y-0 transition-transform">
                  <h3 className="text-xl font-bold text-white mb-1">{member.name}</h3>
                  <p className="text-primary-300 text-sm font-medium">{member.role}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default About;
