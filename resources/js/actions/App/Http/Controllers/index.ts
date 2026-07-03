import MedicalCaseController from './MedicalCaseController'
import Settings from './Settings'

const Controllers = {
    MedicalCaseController: Object.assign(MedicalCaseController, MedicalCaseController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers