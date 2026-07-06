import Api from './Api'
import MedicalCaseController from './MedicalCaseController'
import MedicalNoteController from './MedicalNoteController'
import Settings from './Settings'

const Controllers = {
    Api: Object.assign(Api, Api),
    MedicalCaseController: Object.assign(MedicalCaseController, MedicalCaseController),
    MedicalNoteController: Object.assign(MedicalNoteController, MedicalNoteController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers