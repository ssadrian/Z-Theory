import {IPivot} from './pivot.model';
import {IStudent} from './student.model';

export interface IRanking {
  id: number;
  code: string;
  students: IStudent[];
  pivot: IPivot;
  name: string,
  creator: number
}
