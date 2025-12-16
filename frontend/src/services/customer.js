import axios from 'axios'

class CustomerService {
    async list(searchTerm = '') {
        const params = searchTerm ? { search: searchTerm } : {}
        const response = await axios.get('/api/customers', { params })
        return response.data
    }

    async create(data) {
        const response = await axios.post('/api/customers', data)
        return response.data
    }

    async update(id, data) {
        const response = await axios.put(`/api/customers/${id}`, data)
        return response.data
    }

    async delete(id) {
        const response = await axios.delete(`/api/customers/${id}`)
        return response.data
    }

    async getById(id) {
        const response = await axios.get(`/api/customers/${id}`)
        return response.data
    }
}

export const customerService = new CustomerService()
