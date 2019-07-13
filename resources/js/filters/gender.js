export default function(gender) {
    if (!gender) return '';
    return gender === 'female' ? 'feminino' : 'masculino';
}
