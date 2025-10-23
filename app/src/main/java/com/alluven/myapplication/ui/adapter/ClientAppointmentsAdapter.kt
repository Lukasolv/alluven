package com.alluven.myapplication.ui.adapter

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.alluven.myapplication.R
import com.alluven.myapplication.network.dto.AppointmentDTO

class ClientAppointmentsAdapter(
    private val items: MutableList<AppointmentDTO>,
    private val onClick: (AppointmentDTO) -> Unit = {}
) : RecyclerView.Adapter<ClientAppointmentsAdapter.VH>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): VH {
        val v = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_appointment, parent, false)
        return VH(v)
    }

    override fun onBindViewHolder(h: VH, pos: Int) {
        val item = items[pos]

        h.txtCliente.text = item.customer_name
        h.txtDetalhe.text = listOf(item.service_name, item.date, item.time)
            .filter { s -> s.isNotBlank() }.joinToString(" • ")

        h.btnEditar.visibility = View.GONE
        h.btnExcluir.visibility = View.GONE

        h.itemView.setOnClickListener { onClick(item) }
    }

    override fun getItemCount() = items.size

    fun submit(list: List<AppointmentDTO>) {
        items.clear()
        items.addAll(list)
        notifyDataSetChanged()
    }

    class VH(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val txtCliente: TextView = itemView.findViewById(R.id.txtCliente)
        val txtDetalhe: TextView = itemView.findViewById(R.id.txtDetalhe)
        val btnEditar: Button = itemView.findViewById(R.id.btnEditar)
        val btnExcluir: Button = itemView.findViewById(R.id.btnExcluir)
    }
}
