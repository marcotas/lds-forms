import moment from 'moment';

export default function(date, format = 'LL') {
    if (!date) return '';
    return moment(date).format(format);
}
