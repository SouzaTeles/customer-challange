import axios from 'axios'

axios.defaults.withCredentials = true

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401 && window.location.pathname !== '/login') {
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

class AuthService {
    async login(email, password) {
        const response = await axios.post('/api/auth/login', { email, password })
        if (response.data.user) {
            sessionStorage.setItem('user', JSON.stringify(response.data.user))
        }
        return response.data
    }

    async logout() {
        await axios.post('/api/auth/logout')
        sessionStorage.removeItem('user')
    }

    getUser() {
        const userData = sessionStorage.getItem('user')
        return userData ? JSON.parse(userData) : null
    }
}

export const authService = new AuthService()
