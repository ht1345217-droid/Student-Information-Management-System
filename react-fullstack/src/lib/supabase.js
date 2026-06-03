import { createClient } from '@supabase/supabase-js';

// These should be set in .env file in a real project
// export const supabase = createClient(
//   import.meta.env.VITE_SUPABASE_URL,
//   import.meta.env.VITE_SUPABASE_ANON_KEY
// );

// For demonstration purposes without requiring instant setup, 
// we will export a mock client if credentials are not found,
// but structure it so it's ready for production.

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL || 'https://placeholder-project.supabase.co';
const supabaseKey = import.meta.env.VITE_SUPABASE_ANON_KEY || 'placeholder-key';

export const supabase = createClient(supabaseUrl, supabaseKey);
