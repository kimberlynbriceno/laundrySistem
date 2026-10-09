<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /** muestra clientes registrados.*/

    public function index()
    {
        
        $customers = Customer::all(); 

        return view('customers.index', compact('customers'));
    }

    /**registrar un cliente nuevo.*/

    public function create()
    {
        return view('customers.create');
    }

    /**
     *  guarda el nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|unique:customers,email', //
            'address' => 'nullable|string',
           
        ]);

        Customer::create($validated);

        
        return redirect()->route('customers.index')
                         ->with('success', 'Cliente registrado correctamente.');
    }

    
    public function show(Customer $customer)
    {

        $customer->load('orders');

        return view('customers.show', compact('customer'));
    }

    /**modificar los datos del cliente. */

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**actualizar cliente */

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|unique:customers,email,' . $customer->id, // Ignora el email del propio cliente al validar duplicados
            'address' => 'nullable|string',
            'notes'   => 'nullable|string',
        ]);

       
        $customer->update($validated);

        return redirect()->route('customers.index')
                         ->with('success', 'Datos del cliente actualizados.');
    }

    /**Borra al cliente del sistema.
     */
    public function destroy(Customer $customer)
    {
        
        $customer->delete();

        return redirect()->route('customers.index')
                         ->with('success', 'Cliente eliminado correctamente.');
    }
}

