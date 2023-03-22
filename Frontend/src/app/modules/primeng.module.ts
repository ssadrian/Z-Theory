import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';

import {ButtonModule} from 'primeng/button';
import {CalendarModule} from 'primeng/calendar';
import {CardModule} from 'primeng/card';
import {ContextMenuModule} from 'primeng/contextmenu';
import {DialogModule} from 'primeng/dialog';
import {DropdownModule} from 'primeng/dropdown';
import {FileUploadModule} from 'primeng/fileupload';
import {InputTextModule} from 'primeng/inputtext';
import {MultiSelectModule} from 'primeng/multiselect';
import {PasswordModule} from 'primeng/password';
import {ProgressBarModule} from 'primeng/progressbar';
import {RippleModule} from 'primeng/ripple';
import {SliderModule} from 'primeng/slider';
import {TableModule} from 'primeng/table';
import {ToastModule} from 'primeng/toast';
import {ToolbarModule} from 'primeng/toolbar';
import {StyleClassModule} from 'primeng/styleclass';

@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    TableModule,
    CalendarModule,
    SliderModule,
    DialogModule,
    MultiSelectModule,
    ContextMenuModule,
    DropdownModule,
    ButtonModule,
    ToastModule,
    InputTextModule,
    ProgressBarModule,
    CardModule,
    PasswordModule,
    RippleModule,
    ToolbarModule,
    FileUploadModule,
    StyleClassModule
  ],
  exports: [
    TableModule,
    CalendarModule,
    SliderModule,
    DialogModule,
    MultiSelectModule,
    ContextMenuModule,
    DropdownModule,
    ButtonModule,
    ToastModule,
    InputTextModule,
    ProgressBarModule,
    CardModule,
    PasswordModule,
    RippleModule,
    FileUploadModule,
    ToolbarModule,
    StyleClassModule
  ],
})
export class PrimengModule {
}
