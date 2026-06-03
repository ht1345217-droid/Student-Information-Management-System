import { Link } from 'react-router-dom';

const NotFound = () => {
  return (
    <div className="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-gray-50 dark:bg-dark-900 px-4">
      <div className="text-center">
        <h1 className="text-9xl font-extrabold text-primary-600">404</h1>
        <h2 className="text-3xl font-bold text-gray-900 dark:text-white mt-4">Page Not Found</h2>
        <p className="text-gray-600 dark:text-gray-400 mt-4 mb-8">The page you are looking for doesn't exist or has been moved.</p>
        <Link to="/" className="btn-primary px-8 py-3">Go Back Home</Link>
      </div>
    </div>
  );
};

export default NotFound;
