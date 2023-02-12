import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {LandPageComponent} from './pages/land-page/land-page.component';
import {NotFoundComponent} from './pages/not-found/not-found.component';

const routes: Routes = [
  { path: '', component: LandPageComponent },
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
