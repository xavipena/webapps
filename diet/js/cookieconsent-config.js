/**
 * All config. options available here:
 * https://cookieconsent.orestbida.com/reference/configuration-reference.html
 */
import 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.umd.js';

CookieConsent.run({

    // root: 'body',
    // autoShow: true,
    disablePageInteraction: true,
    // hideFromBots: true,
    // mode: 'opt-in',
    // revision: 0,

    cookie: {
        name: 'cc_cookie',
        // domain: location.hostname,
        // path: '/',
        // sameSite: "Lax",
        // expiresAfterDays: 365,
    },
    guiOptions: {
        consentModal: {
            layout: 'cloud inline',
            position: 'bottom center',
            equalWeightButtons: true,
            flipButtons: false
        },
        preferencesModal: {
            layout: 'box',
            equalWeightButtons: true,
            flipButtons: false
        }
    },

    onFirstConsent: ({cookie}) => {
        console.log('onFirstConsent fired',cookie);
    },

    onConsent: ({cookie}) => {
        console.log('onConsent fired!', cookie)
    },

    onChange: ({changedCategories, changedServices}) => {
        console.log('onChange fired!', changedCategories, changedServices);
    },

    onModalReady: ({modalName}) => {
        console.log('ready:', modalName);
    },

    onModalShow: ({modalName}) => {
        console.log('visible:', modalName);
    },

    onModalHide: ({modalName}) => {
        console.log('hidden:', modalName);
    },

    categories: {
        necessary: {
            enabled: true,  // this category is enabled by default
            readOnly: true  // this category cannot be disabled
        },
        analytics: {
            enabled: false,
            autoClear: {
                cookies: [
                    {
                        name: /^_ga/,   // regex: match all cookies starting with '_ga'
                    },
                    {
                        name: '_gid',   // string: exact cookie name
                    }
                ]
            },
        },
        ads: {}
    },

    language: {
        default: 'es',
        translations: {
            es: {
                consentModal: {
                    title: 'Utilizamos cookies',
                    description: 'Usamos cookies para mejorar tu experiencia de navegación, mostrar contenidos personalizados y analizar nuestro tráfico. Al hacer clic en “Aceptar todo” das tu consentimiento a nuestro uso de las cookies.',
                    acceptAllBtn: 'Aceptar todo',
                    acceptNecessaryBtn: 'Rechazar todo',
                    showPreferencesBtn: 'Gestionar preferencias',
                    // closeIconLabel: 'Reject all and close modal',
                    footer: `
                        <a href="https://diaridigital.net/intelligence/includes/privacidad.php" target="_blank">Política de privacidad</a>
                    `,
                },
                preferencesModal: {
                    title: 'Gestionar preferencias',
                    acceptAllBtn: 'Aceptar todo all',
                    acceptNecessaryBtn: 'Rechazar todo',
                    savePreferencesBtn: 'Aceptar la selección',
                    closeIconLabel: 'Cerrar el diálogo',
                    serviceCounterLabel: 'Service|Services',
                    sections: [
                        {
                            title: 'Tus opciones de privacidad',
                            description: `En este panel puedes cambair algunas preferencias del procesamiento de tu información personal. Puedes revisar y cambiar las opciones en cualquier momento volviendo a este panel a través del enlace proporcionado. Para denegar tu consentimiento a las actividades de procesamiento que se describen a continuación, desactiva las opciones o usa el botón "Rechazar todo" y guarda tus opciones.`,
                        },
                        {
                            title: 'Estrictamente necesarias',
                            description: 'Las cookies necesarias son cruciales para las funciones básicas del sitio web y el sitio web no funcionará de la forma prevista sin ellas. Estas cookies no almacenan ningún dato de identificación personal.',

                            //this field will generate a toggle linked to the 'necessary' category
                            linkedCategory: 'necessary'
                        },
                        {
                            title: 'Rendimiento',
                            description: 'Las cookies de rendimiento se utilizan para comprender y analizar los índices de rendimiento clave del sitio web, lo que ayuda a proporcionar una mejor experiencia de usuario para los visitantes.',
                            linkedCategory: 'analytics',
                            cookieTable: {
                                caption: 'Cookie table',
                                headers: {
                                    name: 'Cookie',
                                    domain: 'Domain',
                                    desc: 'Description'
                                },
                                body: [
                                    {
                                        name: '_ga',
                                        domain: location.hostname,
                                        desc: 'Se usa para distinguir a los usuarios.',
                                    },
                                    {
                                        name: '_gid',
                                        domain: location.hostname,
                                        desc: 'Se usa para mantener el estado de la sesión.',
                                    }
                                ]
                            }
                        },
                        {
                            title: 'Publicidad',
                            description: 'Las cookies publicitarias se utilizan para mostrar a los visitantes anuncios personalizados basados en las páginas que visitaron antes y analizar la efectividad de la campaña publicitaria.',
                            linkedCategory: 'ads',
                        },
                        {
                            title: 'Más información',
                            description: 'Para cualquier consulta en relación con la política de cookies y sus opciones, ponte en contacto con nosotros.'
                        }
                    ]
                }
            },
            ca: {
                consentModal: {
                    title: 'Fem servir cookies',
                    description: 'Fem servir cookies per millorar la teva experiència de navegació, mostrar continguts personalitzats i analitzar el nostre trànsit. En fer clic a “Accepta tot” dones el teu consentiment al nostre ús de les cookies.',
                    acceptAllBtn: 'Accepta tot',
                    acceptNecessaryBtn: 'Rebutja tot',
                    showPreferencesBtn: 'Gestiona els preferències',
                    // closeIconLabel: 'Reject all and close modal',
                    footer: `
                        <a href="https://diaridigital.net/intelligence/includes/privacidad.php" target="_blank">Política de privacitat</a>
                    `,
                },
                preferencesModal: {
                    title: 'Getionar preferències',
                    acceptAllBtn: 'Accepta tot',
                    acceptNecessaryBtn: 'Rebutja all',
                    savePreferencesBtn: 'Accepta la selecció',
                    closeIconLabel: 'Tanca el diàleg',
                    serviceCounterLabel: 'Service|Services',
                    sections: [
                        {
                            title: 'Les teves opcions de privacitat',
                            description: `En aquest panell pots canviar algunes preferències del processament de la teva informació personal. Pots revisar i canviar les opcions en qualsevol moment tornant a aquest panell a través de l'enllaç proporcionat. Per denegar el teu consentiment a les activitats de processament que es descriuen a continuació, desactiva les opcions o fes servir el botó "Rebutja tot" i desa les teves opcions.`,
                        },
                        {
                            title: 'Justes i necessàries',
                            description: 'Les cookies necessàries són crucials per a les funcions bàsiques del lloc web i el lloc web no funcionarà de la manera prevista sense. Aquestes galetes no emmagatzemen cap dada didentificació personal.',

                            //this field will generate a toggle linked to the 'necessary' category
                            linkedCategory: 'necessary'
                        },
                        {
                            title: 'Rendiment',
                            description: 'Les galetes de rendiment sutilitzen per comprendre i analitzar els índexs de rendiment clau del lloc web, la qual cosa ajuda a proporcionar una millor experiència dusuari per als visitants.',
                            linkedCategory: 'analytics',
                            cookieTable: {
                                caption: 'Cookie table',
                                headers: {
                                    name: 'Cookie',
                                    domain: 'Domain',
                                    desc: 'Description'
                                },
                                body: [
                                    {
                                        name: '_ga',
                                        domain: location.hostname,
                                        desc: `S'utilitza per distingir els usuaris.`,
                                    },
                                    {
                                        name: '_gid',
                                        domain: location.hostname,
                                        desc: `S'utilitza per conservar l'estat de la sessió.`,
                                    }
                                ]
                            }
                        },
                        {
                            title: 'Publicitat',
                            description: `Les galetes publicitàries s'utilitzen per mostrar als visitants anuncis personalitzats basats en les pàgines que van visitar abans i analitzar l'efectivitat de la campanya publicitària.`,
                            linkedCategory: 'ads',
                        },
                        {
                            title: 'Més informació',
                            description: `Per a qualsevol consulta en relació amb la política de galetes i les seves opcions, posa't en contacte amb nosaltres.`
                        }
                    ]
                }
            }
        }
    }
});