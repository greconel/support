import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginPdfPreview from "filepond-plugin-pdf-preview";

window.FilePond = FilePond;
FilePond.registerPlugin(FilePondPluginFileValidateType);
FilePond.registerPlugin(FilePondPluginPdfPreview);
FilePond.setOptions({ credits: {} });
