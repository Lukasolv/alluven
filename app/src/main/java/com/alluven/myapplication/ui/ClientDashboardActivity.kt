package com.alluven.myapplication.ui

import android.os.Bundle
import android.view.View
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.alluven.myapplication.R
import com.alluven.myapplication.network.ApiClient
import com.alluven.myapplication.network.ApiService
import com.alluven.myapplication.network.dto.AppointmentDTO
import com.alluven.myapplication.ui.adapter.ClientAppointmentsAdapter
import com.alluven.myapplication.util.SessionManager
import kotlinx.coroutines.launch

class ClientDashboardActivity : AppCompatActivity() {

    private lateinit var api: ApiService
    private lateinit var session: SessionManager
    private lateinit var adapter: ClientAppointmentsAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_client_dashboard)

        session = SessionManager(this)
        api = ApiClient.build(session).create(ApiService::class.java)

        findViewById<TextView>(R.id.txtWelcome)?.text =
            "Olá, " + (session.name ?: "cliente") + "!"

        val rv = findViewById<RecyclerView>(R.id.rvMyAppointments)
        rv.layoutManager = LinearLayoutManager(this)
        adapter = ClientAppointmentsAdapter(mutableListOf()) { appt: AppointmentDTO ->

        }
        rv.adapter = adapter

        val loader = findViewById<ProgressBar>(R.id.progress)
        val emptyView = findViewById<TextView>(R.id.emptyView)

        loader.visibility = View.VISIBLE
        emptyView.visibility = View.GONE

        lifecycleScope.launch {
            try {
                val data: List<AppointmentDTO> = api.getMyAppointments()
                adapter.submit(data)
                emptyView.visibility = if (data.isEmpty()) View.VISIBLE else View.GONE
            } catch (e: Exception) {
                emptyView.visibility = View.VISIBLE
                emptyView.text = "Não foi possível carregar seus agendamentos."
            } finally {
                loader.visibility = View.GONE
            }
        }
    }
}
