import { IPivot } from './pivot.model';
import { IRanking } from './ranking.model';
import { ISkill } from './skill.model';
import { IUser } from './user.model';

export interface IStudent extends IUser {
  birth_date: Date;
  rankings: IRanking[];
  pivot: IPivot;
  skills: ISkill[];
}
