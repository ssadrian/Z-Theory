import {IPivot} from './pivot.model';
import {IStudent} from './student.model';

export interface IRanking {
  id: number;
  code: string;
  name: string;
  creator: number;
  students: IStudent[];
  pivot: IPivot;
}
