import { Resource } from '@/plugins/resource';

export class TeamService extends Resource {
    constructor() {
        super('/teams');
    }
}
