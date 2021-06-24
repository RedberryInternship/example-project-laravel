export default {
  confirmRemovePhone: phoneNumber => `${__('chargers.whitelist.do-u-really')}"${phoneNumber}" ${__('chargers.whitelist.delete-phone')}`,
  successfullyDeleted: __('chargers.whitelist.phone-delete-success'),
  numberAlreadyExists: __('chargers.whitelist.phone-already-exists'),
}