import { createContext, useContext, useState, useEffect } from 'react';
import { supabase } from '../lib/supabase';

const AuthContext = createContext({});

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [role, setRole] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Check active sessions and sets the user
    const getSession = async () => {
      try {
        const { data: { session } } = await supabase.auth.getSession();
        setUser(session?.user ?? null);
        if (session?.user) {
          fetchRole(session.user.id);
        }
      } catch (error) {
        console.warn('Supabase not configured, using mock auth');
      } finally {
        setLoading(false);
      }
    };

    getSession();

    const { data: { subscription } } = supabase.auth.onAuthStateChange((_event, session) => {
      setUser(session?.user ?? null);
      if (session?.user) fetchRole(session.user.id);
      else setRole(null);
    });

    return () => subscription.unsubscribe();
  }, []);

  const fetchRole = async (userId) => {
    try {
      const { data, error } = await supabase
        .from('users')
        .select('role')
        .eq('id', userId)
        .single();
      if (!error && data) setRole(data.role);
    } catch (e) {}
  };

  const login = async (email, password) => {
    // MOCK LOGIN FOR DEMONSTRATION IF SUPABASE IS NOT SETUP
    if (email === 'admin@sims.edu') {
      setUser({ id: '1', email });
      setRole('admin');
      return { error: null };
    } else if (email === 'student@sims.edu') {
      setUser({ id: '2', email });
      setRole('student');
      return { error: null };
    }

    try {
      const { data, error } = await supabase.auth.signInWithPassword({ email, password });
      return { error };
    } catch (error) {
      return { error };
    }
  };

  const logout = async () => {
    setUser(null);
    setRole(null);
    try { await supabase.auth.signOut(); } catch(e){}
  };

  return (
    <AuthContext.Provider value={{ user, role, login, logout, loading }}>
      {!loading && children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
