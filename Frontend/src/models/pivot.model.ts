import {JoinStatus} from "./misc/join-status";

export interface IPivot {
  ranking_id: number;
  student_id: number;
  join_status_id: JoinStatus;
  points: number;
}
