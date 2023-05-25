import { Injectable } from '@angular/core';
import { FileSelectEvent } from 'primeng/fileupload';

@Injectable({
  providedIn: 'root',
})
export class Base64Service {
  async toBase64(event: FileSelectEvent): Promise<string> {
    const files: File[] = event.files;

    if (!files || files.length === 0) {
      return '';
    }

    return await this.#readBase64(files[0]).then((data: string): string => {
      return data;
    });
  }

  async #readBase64(file: any): Promise<any> {
    const reader: FileReader = new FileReader();

    return new Promise((resolve, reject): void => {
      reader.addEventListener(
        'load',
        (): void => {
          resolve(reader.result?.toString() ?? '');
        },
        false
      );
      reader.addEventListener(
        'error',
        (event: ProgressEvent<FileReader>): void => {
          reject(event);
        },
        false
      );

      reader.readAsDataURL(file);
    });
  }
}
