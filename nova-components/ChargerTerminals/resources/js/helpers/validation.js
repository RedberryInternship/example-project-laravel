const validation = {
  isDigit: ( e ) => {
    return e.keyCode >= 48 && e.keyCode <= 57;
  },
  isEnter: ( e ) => {
    return e.keyCode === 13;
  },
  isBackspace: ( e ) => {
    return e.keyCode === 8;
  },
  shouldPrevent: ( e ) => {
    const { isDigit, isEnter, isBackspace } = validation;
    const shouldNotPrevent = isDigit( e ) || isEnter( e ) || isBackspace( e );

    return ! shouldNotPrevent;
  },
  isAlreadyFilled: ( chargerCode, e ) => {
    const { isBackspace, isEnter } = validation;
    return chargerCode.length >= 4 && ! isBackspace( e ) && ! isEnter( e )
  }
};

export default validation;