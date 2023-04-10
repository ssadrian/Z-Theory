import {inject, NgModule, Type} from '@angular/core';
import {CanMatchFn, RouterModule, Routes} from '@angular/router';
import {StudentGuard} from '../guards/student.guard';
import {TeacherGuard} from '../guards/teacher.guard';
import {LoginFormComponent} from './login-form/login-form.component';
import {RegistrationFormComponent} from './registration-form/registration-form.component';

function mapToCanMatch(providers: Array<Type<{ canMatch: CanMatchFn }>>): CanMatchFn[] {
  return providers
    .map(provider => (...params) => inject(provider)
      .canMatch(...params));
}

const routes: Routes = [
  {path: 'register', component: RegistrationFormComponent},
  {path: 'login', component: LoginFormComponent},
  {
    path: '',
    loadChildren: () => import('./student/student-pages-routing.module').then(x => x.StudentPagesRoutingModule),
    canMatch: mapToCanMatch([StudentGuard])
  },
  {
    path: '',
    loadChildren: () => import('./teacher/teacher-pages-routing.module').then(x => x.TeacherPagesRoutingModule),
    canMatch: mapToCanMatch([TeacherGuard])
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PagesRoutingModule {
}
