import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { StudentRegisterFormComponent } from './student-register-form/student-register-form.component';
import { TeacherRegisterFormComponent } from './teacher-register-form/teacher-register-form.component';

const routes: Routes = [
  { path: 'register-student', component: StudentRegisterFormComponent },
  { path: 'register-teacher', component: TeacherRegisterFormComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PagesRoutingModule {}
