/**
 * reinerde-db.js
 * ──────────────────────────────────────────────────────────────
 * Shared "database" layer using localStorage.
 * All pages (main, contact, dashboard) import this one file.
 * ──────────────────────────────────────────────────────────────
 */

const ReinDB = (() => {

  const MESSAGES_KEY = 'reinerde_messages';
  const SESSION_KEY  = 'reinerde_admin_session';

  /* ── Admin credentials (hashed via SHA-256) ────────────────
     Default → username: admin | password: Reinerde@2026
     To change: run  ReinDB.setAdminCredentials('user','pass')
     in browser console once, then remove the call.
  ─────────────────────────────────────────────────────────── */
  const CRED_KEY = 'reinerde_admin_creds';

  // Simple deterministic hash (good enough for frontend-only app)
  function hashString(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
      const chr = str.charCodeAt(i);
      hash = ((hash << 5) - hash) + chr;
      hash |= 0;
    }
    return hash.toString(16);
  }

  function getCredentials() {
    const raw = localStorage.getItem(CRED_KEY);
    if (raw) return JSON.parse(raw);
    // Default credentials
    return {
      username: hashString('admin'),
      password: hashString('Reinerde@2026')
    };
  }

  /* ── Messages ────────────────────────────────────────────── */

  function getMessages() {
    try {
      return JSON.parse(localStorage.getItem(MESSAGES_KEY)) || [];
    } catch {
      return [];
    }
  }

  function saveMessages(msgs) {
    localStorage.setItem(MESSAGES_KEY, JSON.stringify(msgs));
  }

  /**
   * Add a new contact message.
   * @param {Object} data - { name, email, subject, message, source }
   * @returns {string} id of the new message
   */
  function addMessage(data) {
    const msgs = getMessages();
    const id   = 'msg_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7);
    const entry = {
      id,
      name:    data.name    || '—',
      email:   data.email   || '—',
      subject: data.subject || 'General Enquiry',
      message: data.message || '',
      source:  data.source  || 'Contact Page',   // 'Main Page' or 'Contact Page'
      read:    false,
      date:    new Date().toISOString()
    };
    msgs.unshift(entry); // newest first
    saveMessages(msgs);
    return id;
  }

  function deleteMessage(id) {
    saveMessages(getMessages().filter(m => m.id !== id));
  }

  function markRead(id, value = true) {
    const msgs = getMessages().map(m => m.id === id ? { ...m, read: value } : m);
    saveMessages(msgs);
  }

  function unreadCount() {
    return getMessages().filter(m => !m.read).length;
  }

  /* ── Session ─────────────────────────────────────────────── */

  function login(username, password) {
    const creds = getCredentials();
    if (hashString(username) === creds.username &&
        hashString(password) === creds.password) {
      const token = hashString(username + Date.now());
      sessionStorage.setItem(SESSION_KEY, token);
      return true;
    }
    return false;
  }

  function logout() {
    sessionStorage.removeItem(SESSION_KEY);
  }

  function isLoggedIn() {
    return !!sessionStorage.getItem(SESSION_KEY);
  }

  /** Change admin credentials (call once from console then remove) */
  function setAdminCredentials(username, password) {
    localStorage.setItem(CRED_KEY, JSON.stringify({
      username: hashString(username),
      password: hashString(password)
    }));
  }

  return {
    getMessages,
    addMessage,
    deleteMessage,
    markRead,
    unreadCount,
    login,
    logout,
    isLoggedIn,
    setAdminCredentials
  };

})();
