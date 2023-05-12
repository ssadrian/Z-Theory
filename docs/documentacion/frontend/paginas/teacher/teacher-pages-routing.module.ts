import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { TeacherProfileComponent } from './teacher-profile/teacher-profile.component';
import { AssignmentComponent } from './assignment/assignment.component';
import { QueuesListComponent } from './queues-list/queues-list.component';
import { EvaluationHistoryComponent } from '../evaluation-history/evaluation-history.component';

const routes: Routes = [
  { path: 'profile', component: TeacherProfileComponent },
  { path: 'assignments', component: AssignmentComponent },
  { path: 'queues', component: QueuesListComponent },
  { path: 'historial', component: EvaluationHistoryComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TeacherPagesRoutingModule {}
