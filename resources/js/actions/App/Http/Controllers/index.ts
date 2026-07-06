import MedicalCaseController from './MedicalCaseController'
import MedicalNoteController from './MedicalNoteController'
import Settings from './Settings'

const Controllers = {
    MedicalCaseController: Object.assign(MedicalCaseController, MedicalCaseController),
    MedicalNoteController: Object.assign(MedicalNoteController, MedicalNoteController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers