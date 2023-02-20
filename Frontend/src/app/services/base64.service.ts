import {Injectable} from "@angular/core";

@Injectable({
  providedIn: "root",
})
export class Base64Service {
  async toBase64(event: Event): Promise<string> {
    const files: FileList | null = (event.target as HTMLInputElement)?.files;

    if (!files) {
      return "";
    }

    return await this.#readBase64(files.item(0))
      .then((data: string): string => {
        return data;
      });
  }

  async #readBase64(file: any): Promise<string> {
    const reader: FileReader = new FileReader();

    return new Promise((resolve, reject): void => {
      reader.addEventListener("load", (): void => {
        resolve(reader.result?.toString() ?? "");
      }, false);
      reader.addEventListener("error", (event: ProgressEvent<FileReader>): void => {
        reject(event);
      }, false);

      reader.readAsDataURL(file);
    });
  }
}
