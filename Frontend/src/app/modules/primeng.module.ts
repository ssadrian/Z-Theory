import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {AvatarModule} from 'primeng/avatar';
import {BadgeModule} from 'primeng/badge';

import {ButtonModule} from 'primeng/button';
import {CalendarModule} from 'primeng/calendar';
import {CardModule} from 'primeng/card';
import {CheckboxModule} from 'primeng/checkbox';
import {ContextMenuModule} from 'primeng/contextmenu';
import {DialogModule} from 'primeng/dialog';
import {DropdownModule} from 'primeng/dropdown';
import {FileUploadModule} from 'primeng/fileupload';
import {ImageModule} from 'primeng/image';
import {InputTextModule} from 'primeng/inputtext';
import {MultiSelectModule} from 'primeng/multiselect';
import {PasswordModule} from 'primeng/password';
import {ProgressBarModule} from 'primeng/progressbar';
import {RippleModule} from 'primeng/ripple';
import {ScrollTopModule} from 'primeng/scrolltop';
import {SliderModule} from 'primeng/slider';
import {StyleClassModule} from 'primeng/styleclass';
import {TableModule} from 'primeng/table';
import {ToastModule} from 'primeng/toast';
import {ToggleButtonModule} from 'primeng/togglebutton';
import {ToolbarModule} from 'primeng/toolbar';

@NgModule({
  declarations: [],
  imports: [
    CommonModule,
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
    StyleClassModule,
    ToggleButtonModule,
    CheckboxModule,
    ScrollTopModule,
    BadgeModule,
    AvatarModule,
    ImageModule,
  ],
})
export class PrimengModule {
}
