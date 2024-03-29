import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { ConfirmationService, MessageService } from 'primeng/api';
import { AccordionModule } from 'primeng/accordion';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { FooterComponent } from './components/footer/footer.component';
import { LogoComponent } from './components/logo/logo.component';
import { NavbarComponent } from './components/navbar/navbar.component';
import { PrimengModule } from './modules/primeng.module';
import { LandPageComponent } from './pages/land-page/land-page.component';
import { LoginFormComponent } from './pages/login-form/login-form.component';
import { NotFoundComponent } from './pages/not-found/not-found.component';
import { RegistrationFormComponent } from './pages/registration-form/registration-form.component';
import { StudentProfileComponent } from './pages/student/student-profile/student-profile.component';
import { TeacherProfileComponent } from './pages/teacher/teacher-profile/teacher-profile.component';
import { Base64Service } from './services/base64.service';
import { LoginService } from './services/login.service';
import { RegistrationService } from './services/registration.service';
import { RankingService } from './services/repository/ranking.service';
import { StudentService } from './services/repository/student.service';
import { TeacherService } from './services/repository/teacher.service';
import { AssignmentComponent } from './pages/teacher/assignment/assignment.component';
import { MiscService } from './services/misc.service';
import { QueuesListComponent } from './pages/teacher/queues-list/queues-list.component';
import { EvaluationHistoryListComponent } from './components/evaluation-history-list/evaluation-history-list.component';
import { EvaluationHistoryComponent } from './pages/evaluation-history/evaluation-history.component';
import { HeadersInterceptor } from './interceptors/headers/headers.interceptor';
import { UnauthorizedInterceptor } from './interceptors/unauthorized/unauthorized.interceptor';
import { BadRequestInterceptor } from './interceptors/bad-request/bad-request.interceptor';
import { CookieService } from 'ngx-cookie-service';

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
    AssignmentComponent,
    QueuesListComponent,
    EvaluationHistoryListComponent,
    EvaluationHistoryComponent,
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    PrimengModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    AccordionModule,
  ],
  providers: [
    LoginService,
    RegistrationService,
    Base64Service,
    StudentService,
    TeacherService,
    RankingService,
    MessageService,
    ConfirmationService,
    CookieService,
    MiscService,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: HeadersInterceptor,
      multi: true,
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: UnauthorizedInterceptor,
      multi: true,
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: BadRequestInterceptor,
      multi: true,
    },
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}
