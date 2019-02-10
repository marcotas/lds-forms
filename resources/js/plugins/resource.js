import axios from 'axios';

export class Resource {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async all(params = {}) {
        const { data: resources } = await axios.get(this.baseUrl, { params });
        return resources;
    }

    async find(id) {
        const { data: resource } = await axios.get(`${this.baseUrl}/${id}`);
        return resource.data;
    }
}

export default function(baseUrl) {
    return new Resource(baseUrl);
}
