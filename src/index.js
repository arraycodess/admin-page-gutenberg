/**
 * External dependencies
 */
import { useState, render, useEffect, useCallback } from '@wordpress/element';
import { store as coreDataStore } from '@wordpress/core-data'
import { useDispatch, useSelect } from '@wordpress/data';
import { Button, TextControl, Spinner } from '@wordpress/components';

/**
 * Internal dependencies
 */
import '../index.scss';

const initialFields = {
    field1: '',
    field2: '',
};

export function CreatePageForm( { onSaveFinished } ) {
    const [fields, setFields] = useState( initialFields );
    const { saveEntityRecord } = useDispatch( coreDataStore );
    const handleSave = async () => {
        const savedRecord = await saveEntityRecord(
            'root',
            'site',
            {
                'admin_page_guttenberg_fields': {
                    'field1': fields.field1,
                    'field2': fields.field2,
                }
            }
        );
        if ( savedRecord ) {
            onSaveFinished();
        }
    };
    const handleChange = useCallback((newText, fieldKey) => {
        setFields((prevFields) => ({
            ...prevFields,
            [fieldKey]: newText,
        }));
    }, []);


    const { getFields, lastError, isSaving } = useSelect(
        ( select ) => ( {
            getFields: select(coreDataStore)
                .getEditedEntityRecord('root', 'site'),
            lastError: select( coreDataStore )
                .getLastEntitySaveError( 'root', 'site' ),
            isSaving: select( coreDataStore )
                .isSavingEntityRecord( 'root', 'site' ),
        } ),
        []
    );

    useEffect(() => {
        setFields((prevFields) => ({
            ...prevFields,
            field1: getFields?.admin_page_guttenberg_fields?.field1 || '',
            field2: getFields?.admin_page_guttenberg_fields?.field2 || '',
            // Adicione mais campos conforme necessário
        }));
    }, [getFields]);

    return (
        <div className="my-gutenberg-form">
            <TextControl
                label="Title Field 1"
                value={fields.field1}
                onChange={(newText) => handleChange(newText, 'field1')}
            />
            <TextControl
                label="Description Field 2"
                value={fields.field2}
                onChange={(newText) => handleChange(newText, 'field2')}
            />
            { lastError ? (
                <div className="form-error">Error: { lastError.message }</div>
            ) : (
                false
            ) }
            <div className="form-buttons">
                <Button
                    onClick={ handleSave }
                    variant="primary"
                    disabled={ isSaving }
                >
                    { isSaving ? (
                        <>
                            <Spinner/>
                            Saving
                        </>
                    ) : 'Save' }
                </Button>
            </div>
        </div>
    );
}

export function AdminPage() {
    const AfterSaveAction = () => console.log('save');

    return (
        <div>
            <CreatePageForm
                onSaveFinished={ AfterSaveAction }
            />
        </div>
    );
}

window.addEventListener(
    'load',
    function () {
        const root = ReactDOM.createRoot(document.getElementById("admin-page-gutenberg"));
        root.render(
            <AdminPage />
        );
    },
    false
);