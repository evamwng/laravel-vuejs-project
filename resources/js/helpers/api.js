import axios from 'axios'
import Auth from '../store/auth'

export function get(url, data = null) {
    return axios({
        method: 'GET',
        url: url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`
        }
    })
}

export function post(url, data) {
    return axios({
        method: 'POST',
        url: url,
        data: data,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`,
            'Accept' : 'application/json'
        },
        withCredentials: true
    })
}

export function destroy(url) {
    return axios({
        method: 'GET',
        url: url,
        headers: {
            'Authorization': `Bearer ${Auth.state.api_token}`,
            'Accept' : 'application/json'
        },
        withCredentials: true
    })
}
