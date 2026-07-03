import RedirectToIdentityController from './RedirectToIdentityController'
import SsoCallbackController from './SsoCallbackController'
import LogoutController from './LogoutController'

const Controllers = {
    RedirectToIdentityController: Object.assign(RedirectToIdentityController, RedirectToIdentityController),
    SsoCallbackController: Object.assign(SsoCallbackController, SsoCallbackController),
    LogoutController: Object.assign(LogoutController, LogoutController),
}

export default Controllers