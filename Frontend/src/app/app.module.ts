import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NavbarComponent } from './navbar/navbar.component';
import { LogoComponent } from './logo/logo.component';
import { FooterComponent } from './footer/footer.component';
import { NotfoundComponent } from './notfound/notfound.component';
import { StudentRegisterFormComponent } from './pages/student-register-form/student-register-form.component';
import { TeacherRegisterFormComponent } from './pages/teacher-register-form/teacher-register-form.component';
import { MaterialModule } from './material/material.module';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    LogoComponent,
    FooterComponent,
    NotfoundComponent,
    StudentRegisterFormComponent,
    TeacherRegisterFormComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    MaterialModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
