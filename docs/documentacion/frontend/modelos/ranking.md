import {IPivot} from './pivot.model';
import {IStudent} from './student.model';
import {IAssignment} from "./assignment.model";

export interface IRanking {
  id: number;
  code: string;
  name: string;
  creator: number;
  assignments: IAssignment[];
  queue: IStudent[];
  students: IStudent[];
  pivot: IPivot;
}
