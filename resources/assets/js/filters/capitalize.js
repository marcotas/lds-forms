import _ from 'lodash';

export default function(value) {
    if (!value) return '';
    return value.replace(/_/gim, ' ').replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
}
