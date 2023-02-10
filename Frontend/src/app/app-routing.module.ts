import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { NotFoundComponent } from './pages/not-found/not-found.component';
import {LandPageComponent} from "./pages/land-page/land-page.component";
import { TeacherProfileComponent } from './pages/teacher/teacher-profile/teacher-profile.component';
import { StudentProfileComponent } from './pages/student/student-profile/student-profile.component';

const routes: Routes = [
  // { path: '', component: StudentProfileComponent },
  { path: '', component: TeacherProfileComponent },  
  // {path:'', component: LandPageComponent},

  {
    path: '',
    loadChildren: () =>
      import('./pages/pages-routing.module').then((m) => m.PagesRoutingModule),
  },
  { path: '**', component: NotFoundComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
