import {HttpClientModule} from '@angular/common/http';
import {NgModule} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {BrowserModule} from '@angular/platform-browser';
import {FileUploadModule} from 'primeng/fileupload';
import {StyleClassModule} from 'primeng/styleclass';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {FooterComponent} from './components/footer/footer.component';
import {LogoComponent} from './components/logo/logo.component';
import {NavbarComponent} from './components/navbar/navbar.component';
import {PrimengModule} from './modules/primeng.module';
import {LandPageComponent} from './pages/land-page/land-page.component';
import {LoginFormComponent} from './pages/login-form/login-form.component';
import {NotFoundComponent} from './pages/not-found/not-found.component';
import {Base64Service} from './services/base64.service';
import {LoginService} from './services/login.service';
import {RankingService} from './services/repository/ranking.service';
import {RegistrationService} from './services/registration.service';
import {RegistrationFormComponent} from './pages/registration-form/registration-form.component';
import {StudentProfileComponent} from './pages/student/student-profile/student-profile.component';
import {TeacherProfileComponent} from './pages/teacher/teacher-profile/teacher-profile.component';
import {StudentService} from './services/repository/student.service';
import {TeacherService} from './services/repository/teacher.service';
import {MessageService} from 'primeng/api';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    LogoComponent,
    FooterComponent,
    NotFoundComponent,
    LandPageComponent,
    LoginFormComponent,
    StudentProfileComponent,
    TeacherProfileComponent,
    RegistrationFormComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    PrimengModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    FileUploadModule,
    StyleClassModule,
  ],
  providers: [
    LoginService,
    RegistrationService,
    Base64Service,
    StudentService,
    TeacherService,
    RankingService,
    MessageService,
  ],
  bootstrap: [AppComponent],
})
export class AppModule {
}
