import Errors from './Errors';

export default class {
  constructor(data = {}, format = 'json') {
    this.errors = new Errors();
    this.submitting = false;
    this.__data = data;
    this.__multipart = format.toLowerCase() === 'multipart';
  }

  get __data() {
    let object = {};
    const ignoreAttributes = ['errors', 'submitting', '__multipart', '__data'];
    for (let attr in this) {
      if (ignoreAttributes.includes(attr)) continue;
      object[attr] = this[attr];
    }
    return object;
  }
  set __data(object) {
    for (let field in object) {
      this[field] = object[field];
    }
  }

  data() {
    return this.__multipart ? this.multipartData() : this.jsonData();
  }

  jsonData() {
    let data = {};

    for (let property in this.__data) {
      data[property] = this[property];
    }

    return data;
  }

  multipartData() {
    let data = new FormData();

    for (let property in this.__data) {
      if (typeof (this[property]) === 'object' && !(this[property] instanceof File)) {
        for (let sub in this[property]) {
          data.append(`${property}[${sub}]`, this[property][sub]);
        }
      } else {
        data.append(property, this[property]);
      }
    }

    return data;
  }

  reset() {
    for (let field in this.__data) {
      this[field] = '';
    }

    this.errors.clear();
  }

  post(url, fieldName = null) {
    return this.submit('post', url, fieldName);
  }

  put(url, fieldName = null) {
    return this.submit('put', url, fieldName);
  }

  patch(url, fieldName = null) {
    return this.submit('patch', url, fieldName);
  }

  delete(url, fieldName = null) {
    return this.submit('delete', url, fieldName);
  }

  submit(requestType, url, fieldName = null) {
    const data = fieldName ? { [fieldName]: this.data() } : this.data();
    this.submitting = true;
    this.errors.clear();
    return new Promise((resolve, reject) => {
      axios[requestType](url, data)
        .then(response => {
          this.submitting = false;

          resolve(response);
        })
        .catch(error => {
          this.submitting = false;
          if (error.response.status === 422) this.onFail(error.response.data.errors);

          reject(error.response);
        });
    });
  }

  onFail(errors) {
    this.errors.record(errors);
  }
}
